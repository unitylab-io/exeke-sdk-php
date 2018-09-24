<?php

namespace Exeke\Types;

class Account extends AbstractType {
  public $id = null;
  public $name = null;
  public $commissionRates = null;
  public $secretKey = null;
  public $leadsCount = null;
  public $webhooksCount = null;
  public $trackersCount = null;
  public $metadata = null;
  public $data = null;
  public $createdAt = null;
  public $updatedAt = null;
}
