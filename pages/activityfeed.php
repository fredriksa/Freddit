<?php
  /** 
   * Implements a webpage that shows the latest activity feed.
   */
  header("refresh:5;url=/pages/activityfeed.php");
  
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';
  include_once dirname(__DIR__, 1) . '/src/modelloader.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  include_once dirname(__DIR__, 1) . '/src/models/subfreddit.php';

  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');
    
  $html = file_get_contents('../html/activityfeed.html');
  $html_pieces = explode('<!--===explodePost===-->', $html);

  echo $html_pieces[0];

  $post_ids = ModelLoader::loadAll(
    'post', 
    null,
    'created',
    'DESC', 
    10
  );

  foreach ($post_ids as $id) {
    $post = new Post($id);

    $piece = $html_pieces[1];
    $piece = str_replace('---$postTitle---', $post->get('title'), $piece);
    $piece = str_replace('---$post_id---', $post->get('id'), $piece);

    $owner = new User(intval($post->get('owner')));
    $piece = str_replace('---$postCreator---', $owner->get('username'), $piece);
    $piece = str_replace('---$postCreatedAt---', $post->get('created'), $piece);

    $subfreddit = new Subfreddit($post->get('subfreddit'));
    $piece = str_replace('---$subfredditName---', $subfreddit->get('name'), $piece);
    echo $piece;
  }

  echo $html_pieces[2];
?>