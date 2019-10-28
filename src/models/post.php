<?php
/**
 * Post represents a user model in the Freddit system.
 */
include_once dirname(__DIR__, 1) . '/model.php';
include_once dirname(__DIR__, 1) . '/dataloader.php';
include_once dirname(__DIR__, 1) . '/datamapstore.php';

class Post extends Model {
  /**
   * __construct
   * Constructs a new Post instance and loads it's data.
   *
   * @param  Integer $id The id of the Post to be constructed.
   *
   * @return void Returns nothing.
   */
  public function __construct($id = -1) {
    $dataMap = DataMapStore::getInstance()->getMapping('post');
    $data = DataLoader::load($dataMap, $id);
    $this->setData($data, $dataMap);
  }
}
?>