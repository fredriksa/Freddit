<?php
  /**
   * Implements a webpage that shows a specific request post.
   */

  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/modelloader.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  include_once dirname(__DIR__, 1) . '/src/datamapstore.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';
  include_once dirname(__DIR__, 1) . '/src/models/subfreddit.php';
  include_once dirname(__DIR__, 1) . '/src/models/comment.php';

  if (!SessionHelper::isLoggedIn())
    Redirecthelper::redirect('index.php');

  $post = new Post($_GET['post_id']);
  $subfreddit = new Subfreddit($post->get('subfreddit'));
  $owner = new User($post->get('owner'));

  $html = file_get_contents(dirname(__DIR__, 1) . '/html/post.html');
  $html_pieces = explode('<!--===explodePost===-->', $html);

  $html_pieces[0] = str_replace('---$href---', '/pages/subfreddit.php?subfreddit=' . $subfreddit->get('name'), $html_pieces[0]);
  $html_pieces[0] = str_replace('---$mainContent---', $post->get('content'), $html_pieces[0]); 
  $html_pieces[0] = str_replace('---$postTitle---', $post->get('title'), $html_pieces[0]);
  $html_pieces[0] = str_replace('---$author---', $owner->get('username'), $html_pieces[0]);
  $html_pieces[0] = str_replace('---$createdTime---', $post->get('created'), $html_pieces[0]);

  if (null != $post->get('file')) {
    $html_pieces[0] = str_replace('---$file_path---', "<img src='/images/" . $post->get('file') . "'/>", $html_pieces[0]);
  } else {
    $html_pieces[0] = str_replace('---$file_path---', '', $html_pieces[0]);
  }
  
  $comment_ids = ModelLoader::loadAll(
    'comment',
    DataMapStore::getInstance()->getMapping('comment')->get('post') . '=' . $post->get('id'),
    'created',
    'DESC',
    500
  );
  
  if (isset($comment_ids)) {
    $html_pieces[0] = str_replace('---$tableHead---', file_get_contents(dirname(__DIR__, 1) . '/html/components/posttablehead.html'), $html_pieces[0]);
  } else {
    $html_pieces[0] = str_replace('---$tableHead---', '', $html_pieces[0]);
  }

  echo $html_pieces[0];
  
  if (isset($comment_ids)) {
    foreach ($comment_ids as $id) {
      $comment = new Comment($id);

      $piece = $html_pieces[1];

      $owner = new User(intval($comment->get('owner')));
      $piece = str_replace('---$commentCreator---', $owner->get('username'), $piece);
      $piece = str_replace('---$commentCreatedAt---', $comment->get('created'), $piece);
      $piece = str_replace('---$commentContent---', $comment->get('content'), $piece);
      $piece = str_replace('---$removeButton---', '', $piece);
      $piece = str_replace('---$post_id---', $post->get('id'), $piece);
      echo $piece;
    }
  }

  echo $html_pieces[2];
  $html_new_comment = file_get_contents(dirname(__DIR__, 1) . '/html/components/newcomment.html');
  $html_new_comment = str_replace('---$post_id---', $_GET['post_id'], $html_new_comment);
  echo $html_new_comment;
?>