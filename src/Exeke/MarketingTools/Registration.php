<?php

namespace Exeke\MarketingTools;

class RegistrationResponse {
  public $redirectUrl = null;

  public function __construct($data) {
    $this->redirectUrl = $data['redirect_url'];
  }

  public function getRedirectUrl() {
    return $this->redirectUrl;
  }
}

class RegistrationError extends \Exception {
}

class Registration {
  private $client = null;

  public function __construct($options = []) {
    if (isset($options['client'])) {
      $this->client = $options['client'];
    }
  }

  /**
   * check if an email is available
   * accepted parameters:
   *  - +email_sha256+: a SHA256 representation (string, 64 characters) of a lowercased email address
   *  - +email_address+: an email address
   * @param array $params
   * @return bool
   */
  public function isEmailAvailable($params) {
    $response = $this->getClient()->get(
      'v1/marketing_tools/email/available', $params
    );
    $responseData = \json_decode($response->getBody()->getContents(), true);
    return $responseData['available'];
  }

  /**
   * hash an email address with SHA256 algorithm and request API
   * NOTE: this method is GDPR compliant (email is hashed)
   * @param string $emailAddress An email addres to test: john@example.com
   * @return bool
   */
  public function isEmailAvailableBySHA256($emailAddress) {
    return $this->isEmailAvailable([
      'email_sha256' => hash('sha256', strtolower($emailAddress))
    ]);
  }

  /**
   * check if an email is available
   * NOTE: this method is not GDPR compliant (email is in clear text, not hashed)
   * @param string $emailAddress An email address (john@example.com)
   * @return bool
   */
  public function isEmailAvailableByText($emailAddress) {
    return $this->isEmailAvailable([
      'email_address' => strtolower($emailAddress)
    ]);
  }

  public function create($params) {
    try {
      $response = $this->getClient()->post(
        'v1/marketing_tools/registration', $params
      );
      $responseData = json_decode($response->getBody()->getContents(), true);
      return new RegistrationResponse($responseData);
    } catch(\GuzzleHttp\Exception\ClientException $exception) {
      throw new RegistrationError(
        $exception->getResponse()->getBody()->getContents(),
        $exception->getResponse()->getStatusCode()
      );
    }
  }

  private function getClient() {
    if (is_null($this->client)) {
      $this->client = new \Exeke\Client();
    }
    return $this->client;
  }
}
