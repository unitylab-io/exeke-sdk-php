<?php

namespace Exeke\Types;

class ListPayoutsResponse extends AbstractType {
  public $total = null;
  public $lastEvaluatedKey = null;
  public $payouts = null;

  public function setPayouts($array) {
    $this->payouts = array_map(function($item){
      return new Payout($item);
    }, $array);
  }
}
