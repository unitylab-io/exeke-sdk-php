<?php

namespace Exeke;

class Client {
  const DEFAULT_ENDPOINT = 'https://api.exeke.com';
  /**
   * @var \GuzzleHttp\Client
   */
  public $adapter = null;
  private $apiKey = null;
  private $endpoint = null;
  private $accountId = null;
  private $apiKeySecret = null;
  private $apiKeyData = null;

  public function __construct($options = []) {
    $this->endpoint = isset($options['endpoint']) ? $options['endpoint'] : self::DEFAULT_ENDPOINT;
    if (!isset($options['api_key']) && getenv('EXEKE_API_KEY') == false) {
      throw new \Exception('an api key must be provided');
    }
    $this->apiKey = isset($options['api_key']) ? $options['api_key'] : getenv('EXEKE_API_KEY');
  }

  public function whoami() {
    $response = $this->get('v1/account');
    $responseData = json_decode($response->getBody()->getContents(), true);
    return new Types\Account($responseData['account']);
  }

  /**
   * @return Types\ListPayoutsResponse
   */
  public function listPayouts(\DateTime $fromDate, $options = []) {
    $params = array_merge($options, [ 'from_date' => $fromDate->format('c') ]);
    $response = $this->get('v1/payouts', $params);
    $responseData = json_decode($response->getBody()->getContents(), true);
    return new Types\ListPayoutsResponse($responseData);
  }

  public function get($path, $params = []) {
    return $this->getAdapter()->get($this->urlFor($path), [ 'query' => $params ]);
  }

  public function post($path, $data = null) {
    return $this->getAdapter()->post(
      $this->urlFor($path),
      [
        \GuzzleHttp\RequestOptions::JSON => $data
      ]
    );
  }

  public function put($path, $data = null) {
    return $this->getAdapter()->put(
      $this->urlFor($path),
      [
        'body' => \json_encode($data)
      ]
    );
  }

  public function delete($path, $options = []) {
    return $this->getAdapter()->delete($this->urlFor($path), $options);
  }

  public function getAdapter() {
    if (is_null($this->adapter)) {
      $this->adapter = new \GuzzleHttp\Client([
        'headers' => [
          'Authorization' => "Basic " . base64_encode($this->getAccountId() . ':' . $this->getApiKeySecret())
        ]
      ]);
    }
    return $this->adapter;
  }

  private function getApiKeyData() {
    if (is_null($this->apiKeyData)) {
      list($accountId, $secret) = \explode('.', $this->apiKey);
      $this->apiKeyData = new \stdClass();
      $this->apiKeyData->accountId = $accountId;
      $this->apiKeyData->secret = $secret;
    }
    return $this->apiKeyData;
  }

  private function urlFor($path) {
    return sprintf('%s/%s', $this->endpoint, $path);
  }

  private function getAccountId() {
    return $this->getApiKeyData()->accountId;
  }

  private function getApiKeySecret() {
    return $this->getApiKeyData()->secret;
  }
}
