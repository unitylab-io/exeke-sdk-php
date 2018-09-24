<?php

namespace Exeke\Types;

class Payout extends AbstractType {
  public $id = null;
  public $accountId = null;
  public $campaignId = null;
  public $type = null;
  public $productUrn = null;
  public $transactionId = null;
  public $transactionAmount = null;
  public $transactionTax = null;
  public $commissionRate = null;
  public $trackers = null;
  public $data = null;
  public $createdAt = null;
  public $amount = null;
}
