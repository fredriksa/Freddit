<?php
include_once('databaseconnector.php');
include_once('datamap.php');

/**
 * DataLoader is responsible for loading data from a database.
 */
class DataLoader {  
  /**
   * load
   * Fetches all model data with the requested information from the database
   * according to a datamapper specificaiton.
   *
   * 
   * @param  DataMap the data mapper containing the value mapping for the model.
   * @param  mixed $value The value of the primary identifier to match the entity to load the data from.
   *
   * Additional: A DataMap's keys are required to prevent that renaming of columns in the database changes
   *        code functionality in other places than model definition.
   * 
   * @return mixed An array with all the requested data or null if no data was loaded.
   */
  public static function load($dataMap, $value) {
    $db = DatabaseConnector::getInstance();
    $columns = $dataMap->getColumns();
    $table = $dataMap->getCore()['table'];
    $primary = $dataMap->getCore()['primary'];
    $stmt = $db->prepare(DataLoader::buildSelectString($table, $columns, $primary, $value));
    $stmt->execute(array($value));
    $rows = $stmt->fetchAll();

    if (count($rows) == 0)
      return null;
    
    $data = array();
    $keys = $dataMap->getKeys();
    for($i = 0; $i < count($columns); $i++) {
      $columnName = $keys[$i]['column_name'];
      $data[$columnName] = $rows[0][$i];
    }
    
    return $data;
  }

  /**
   * buildSelectString
   * Builds a select string with the given values.
   *
   * @param  mixed $table The table to select the data from.
   * @param  mixed $columns The columns to be selected.
   * @param  mixed $primary The primary column that identifies a single entity, e.g. 'id'.
   *
   * @return string The select string, e.g. 'SELECT c1, c2, c3 FROM user WHERE id=?'
   */
  private static function buildSelectString($table, $columns, $primary) {
    $query = 'SELECT ';
    for ($i = 0; $i < count($columns); $i++) {
      $query .= $columns[$i]['column_name'];
      
      if ($i != count($columns)-1)
        $query .= ', ';
      else
        $query .= ' ';
    }

    $query .= 'FROM ' . $table;
    $query .= ' WHERE ' . $primary . '=?';
    return $query;
  }  
}
?>