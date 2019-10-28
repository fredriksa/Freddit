<?php
  /** 
   * Implements a route that handles creation of new posts.
   */
  
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/model.php';
  include_once dirname(__DIR__, 1) . '/src/models/subfreddit.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';

  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');

  $id = Model::getAttributeValue('subfreddit', 'id', 'name', $_POST['subfreddit']);
  
  if (!isset($id)) {
    $subfreddit = new Subfreddit();
    $subfreddit->set('name', $_POST['subfreddit']);
    $subfreddit->saveNewToDB();
  } else 
    $subfreddit = new Subfreddit($id);

  $post = new Post();
  $post->set('title', $_POST['title']);
  $post->set('content',$_POST['content']);
  $post->set('owner', SessionHelper::getUser()->get('id'));
  $post->set('created', date("Y-m-d H:i:s"));
  $post->set('subfreddit', $id);

  $file = $_FILES['file'];
  if ($file['size'] != 0 && ($file['type'] == 'image/png' || $file['type'] == 'image/jpeg')) {
    $config = include dirname(__DIR__, 1) . '/config.php';
    $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $config['IMAGE_PATH'];
    $file_name = SessionHelper::getUser()->get('id') . $file['name']; 
    $status = move_uploaded_file($file['tmp_name'], $upload_path . $file_name);
    $post->set('file', $file_name);
  }

  $post->saveNewToDB();
  RedirectHelper::redirectFull($_SERVER['HTTP_REFERER']);
?>