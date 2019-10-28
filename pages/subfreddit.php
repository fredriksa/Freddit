<?php
  /**
   * Implements a webpage that shows a specific request subfreddit.
   */

  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/modelloader.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  include_once dirname(__DIR__, 1) . '/src/datamapstore.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';

  if (!SessionHelper::isLoggedIn())
    Redirecthelper::redirect('index.php');

  $subfreddit = $_GET['subfreddit'];
  if (!isset($subfreddit) || strlen($subfreddit) < 1) 
    Redirecthelper::redirect('/pages/frontpage.php');

  $subfreddit = strtoupper($subfreddit[0]) . substr($subfreddit, 1, strlen($subfreddit)-1);
  
  $html = file_get_contents(dirname(__DIR__, 1) . '/html/subfreddit.html');
  $html_pieces = explode('<!--===explodePost===-->', $html);

  $html_pieces[0] = str_replace('---$subfredditName---', $subfreddit, $html_pieces[0]);
  
  $subfredditId = Model::getAttributeValue('subfreddit', 'id', 'name', $subfreddit);
  $post_ids = ModelLoader::loadAll(
    'post', 
    DataMapStore::getInstance()->getMapping('post')->get('subfreddit') . '=' . $subfredditId,
    'created',
    'DESC', 
    500
  );

  $html_piece_remove_button = file_get_contents(dirname(__DIR__, 1) . '/html/components/subfredditremovebutton.html');
  if (isset($post_ids)) {
    $html_pieces[0] = str_replace('---$tablehead---', file_get_contents(dirname(__DIR__, 1) . '/html/components/subfreddittableheadposts.html'), $html_pieces[0]);
    echo $html_pieces[0];

    foreach ($post_ids as $id) {
      $post = new Post($id);
      $piece = $html_pieces[1];
      $piece = str_replace('---$postTitle---', $post->get('title'), $piece);

      $owner = new User(intval($post->get('owner')));
      $piece = str_replace('---$postCreator---', $owner->get('username'), $piece);
      $piece = str_replace('---$postCreatedAt---', $post->get('created'), $piece);
      
      if (SessionHelper::getUser()->isOwnerOf($post)) {
        $piece = str_replace('---$removeButton---', $html_piece_remove_button, $piece);
      } else {
        $piece = str_replace('---$removeButton---', '', $piece);
      }

      $piece = str_replace('---$post_id---', $post->get('id'), $piece);
      echo $piece;
    }
  } else {
    $html_pieces[0] = str_replace('---$tablehead---', '', $html_pieces[0]);
    echo $html_pieces[0];
    echo file_get_contents(dirname(__DIR__, 1) . '/html/components/nosubfredditposts.html');
  }


  $newposthtml = file_get_contents(dirname(__DIR__, 1) . '/html/components/newpost.html');

  $newposthtml = str_replace('---$referer---', 'pages/' . basename($_SERVER['REQUEST_URI']), $newposthtml);
  $newposthtml = str_replace('---$subfreddit---', $subfreddit, $newposthtml); 
  echo $html_pieces[2];
  echo $newposthtml;
?>