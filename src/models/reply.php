<?php
/**
 * Reply represents a reply model in the Freddit system.
 */
include_once dirname(__DIR__, 1) . '/model.php';
include_once dirname(__DIR__, 1) . '/dataloader.php';

class Reply {
  /**
   * __construct
   * Constructs a new Reply instance and loads it's data.
   *
   * @param  Integer $id The id of the Reply to be constructed and loaded.
   *
   * @return void Returns nothing.
   */
  public function __construct($id) {
    $dataMap = DataMapStore::getInstance()->getMapping('reply');
    $data = DataLoader::load($dataMap, $id);
    $this->setData($data, $dataMap);
  }
}
?>