<?php
/**
 * Subfreddit represents a subfreddit model in the Freddit system.
 */
include_once dirname(__DIR__, 1) . '/model.php';
include_once dirname(__DIR__, 1) . '/dataloader.php';
include_once dirname(__DIR__, 1) . '/databaseconnector.php';

class Subfreddit extends Model {
  /**
   * __construct
   * Constructs a new Subfreddit instance and loads it's data.
   *
   * @param  Integer $id The id of the Subfreddit to be constructed, 
   * if not provided does not attempt to load data from the database.
   *
   * @return void Returns nothing.
   */
  public function __construct($id = -1) {
    $dataMap = DataMapStore::getInstance()->getMapping('subfreddit');
    $data = $id != -1 ? DataLoader::load($dataMap, $id) : array();
    $this->setData($data, $dataMap);
  }
}
?>