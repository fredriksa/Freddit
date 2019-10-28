<?php
/**
 * User represents a user model in the Freddit system.
 */
include_once dirname(__DIR__, 1) . '/model.php';
include_once dirname(__DIR__, 1) . '/dataloader.php';
include_once dirname(__DIR__, 1) . '/databaseconnector.php';
include_once dirname(__DIR__, 1) . '/datamapstore.php';

class User extends Model {
  /**
   * __construct
   * Constructs a new User instance and loads it's data from the database.
   *
   * @param  mixed $id The id of the user to be constructed and loaded.
   *
   * @return void Returns nothing.
   */
  public function __construct($id) {
    $dataMap = DataMapStore::getInstance()->getMapping('user');
    $data = DataLoader::load($dataMap, $id);
    $this->setData($data, $dataMap);
  }

  
  /**
   * isOwnerOf
   * checks if the user is the owner of a Post instance.
   * @param  Post $post
   *
   * @return boolean True if is the owner false if not
   */
  public function isOwnerOf($post) {
    return  $post->get('owner') == $this->get('id');
  }
}
?>