<?php
/**
 * DataMap is responsible for providing the functionality required to separate model
 * attribute keys from the actual table names, while having the ability to lookup column names.
 */
class DataMap {
  private $mapping;
  private $core;

  /**
   * __construct
   * Prepares a data map between programming values and the actual database structure.
   * 
   * @param  mixed $keys The keys to be used for retrieving their responding column names.
   * @param  mixed $columns The columns for the keys.
   *
   * @return void Returns nothing.
   */
  public function __construct($keys, $columns) {
    $this->mapping = array();
    if (count($keys) <= count($columns)) {
      for ($i = 0; $i < count($keys); $i++) {
        $this->mapping[$keys[$i]] = $columns[$i];
      }
    }
  }

  /**
   * getValue
   * Fetches the column name for a mapped key.
   * @param  mixed $key The key to fetch the column name for.
   *
   * @return mixed The column name for the key or null if key does not exist.
   */
  public function get($key) {
    if (!array_key_exists($key, $this->mapping))
      return null;

    return $this->mapping[$key]['column_name'];
  }

  /**
   * getColumns
   * Get all the column names for this datamapper.
   * @return array The array with the database columns for this datamapper.
   */
  public function getColumns() {
    $columns = array();
    foreach($this->mapping as $key => $value) {
      array_push($columns, $value);
    }
    return $columns;
  }

  /**
   * getKeys
   * Gets all the keys for this datamapper.
   *
   * @return array The array with the keys for this datamapper.
   */
  public function getKeys() {
    $keys = array();
    foreach($this->mapping as $key => $value) {
      array_push($keys, $value);
    }
    return $keys;
  }

    /**
   * setCore
   * Sets the core array.
   * 
   * @param  array $core The core array to be set.  
   *
   * @return void Returns nothing.
   */
  public function setCore($core) {
    $this->core = $core;
  }

  /**
   * getCore
   * Fetches the core array.
   *
   * @return array Returns the core array.
   */
  public function getCore() {
    return $this->core;
  } 
}
?>