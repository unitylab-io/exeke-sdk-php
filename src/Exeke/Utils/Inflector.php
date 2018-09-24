<?php

namespace Exeke\Utils;

class Inflector {
    /**
   * Converts a word into the format for a Doctrine table name. Converts 'ModelName' to 'model_name'.
   */
  public static function tableize($word) {
    return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
  }

  /**
   * Converts a word into the format for a Doctrine class name. Converts 'table_name' to 'TableName'.
   */
  public static function classify($word) {
    return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
  }

  /**
   * Camelizes a word. This uses the classify() method and turns the first character to lowercase.
   */
  public static function camelize($word) {
    return lcfirst(self::classify($word));
  }

  public static function ucwords($string, $delimiters = " \n\t\r\0\x0B-") {
    return ucwords($string, $delimiters);
  }
}
