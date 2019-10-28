<?php
include_once('datamap.php');
include_once('datamapstore.php');
include_once('databaseconnector.php');

/**
 * Model represents a generic model that provides base functionality for all models.
 */
class Model {
  protected $data = null;
  private $dataMap = null;

  private function __construct() { }

  /**
   * setData
   * Sets the data and DataMapper for this model.
   *
   * @param  Array $data The data array to be set for this model.
   * @param DataMap $dataMap The data map to be set for this model.
   *
   * @return void Returns nothing.
   */
  protected function setData($data, $dataMap) {
    $this->data = $data;
    $this->dataMap = $dataMap;
  }

  /**
   * get
   * Fetches the model's stored value identified by key.
   * 
   * @param  mixed $key The key the value is identified by.
   *
   * @return mixed The value or null if no value is found for the key.
   */
  public function get($key) {
    return $this->data[$this->dataMap->get($key)];
  }

  /**
   * set
   * Sets the value for the given key.
   *
   * @param  mixed $key The key to set the value for.
   * @param  mixed $value The value to be set.
   *
   * @return void Returns nothing.
   */
  public function set($key, $value) {
    $this->data[$this->dataMap->get($key)] = $value;
  }

  /**
   * saveNewToDB
   * Saves the new model to the database.
   *
   * @return bool True if successful false if not.
   */
  public function saveNewToDB() {
    $db = DatabaseConnector::getInstance();
    $keysToSave = $this->dataMap->getKeys();
    // Should remove primary key in another way than splice    
    $keysToSave = array_splice($keysToSave, 1);

    // Prepare columns to insert values into
    $sql = 'INSERT INTO ' . $this->dataMap->getCore()['table'] . ' (';
    for($i = 0; $i < count($keysToSave); $i++) {
      $sql .= $keysToSave[$i]['column_name'];
      if ($i + 1 != count($keysToSave))
        $sql .= ', ';
    }

    // Prepare values part of query
    $sql .= ') VALUES (';
    for($i = 0; $i < count($keysToSave); $i++) {
      $columnToSave = $keysToSave[$i];

      switch ($columnToSave['type']) {
        case 'string':
          $sql .= "'" . $this->get($columnToSave['name']) . "'";
          break;
        default:
          $sql .= $this->get($columnToSave['name']);
          break;
      }

      if ($i + 1 != count($keysToSave))
        $sql .= ', ';
    }
    $sql .= ')';    

    $stmt = $db->prepare($sql);
    $stmt->execute();
    return true;
  }

  /**
   * isNewModel
   * Returns whether the model is new or not.
   * 
   * @return bool True if new false if not.
   */
  public function isNewModel() {
    return $this->data->get($this->dataMap->getCore()['primary']) == null;
  }

  /**
  * getFromDB
  * Fetches a attribute value from the database.
  * 
  * @param  string $model The name of the model to fetch the attribute for.
  * @param  string $attribute The name of the attribute to fetch the column value for.
  *    E.g. call with 'owner' if the mapped key is 'owner' but the column is 'owner_id'.
  * @param  mixed $identifier The name of the attribute to use as identifier.
  * @param  mixed $value The value of the primary key used to identify the unique model entity.
  * 
  * @return mixed The value of the column or null if failed.
  */
  public static function getAttributeValue($model, $attribute, $identifier, $value) {
    $db = DatabaseConnector::getInstance();

    $dataMap = DataMapStore::getInstance()->getMapping($model);
    $attr = $dataMap->get($attribute);
    $table = $dataMap->getCore()['table'];
    $identifier = $dataMap->get($identifier);

    // Don't need to bind $model, $attribute or $identifier as those values are explicitly decided server-side.
    $sql = 'SELECT ' . $attr . ' FROM ' . $table . ' WHERE ' . $identifier . '=?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($value));
    $rows = $stmt->fetchAll();
    
    if (count($rows) == 0)
      return null;
    
    return $rows[0][$dataMap->get($attribute)];
  }

  /**
   * delete
   * Deletes the model from the database
   * 
   * @return void Returns nothing.
   */
  public function delete() {
    $db = DatabaseConnector::getInstance();
    $core = $this->dataMap->getCore();

    $sql = 'DELETE FROM ' . $core['table'] . ' WHERE ' . $core['primary'] . ' = ' . $this->get($core['primary']);
    $stmt = $db->prepare($sql);
    $stmt->execute();
  }
}
?>