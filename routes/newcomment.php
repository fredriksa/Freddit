<?php
  /** 
   * Implements a webpage that handles creation of new posts.
   */
  
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/model.php';
  include_once dirname(__DIR__, 1) . '/src/models/subfreddit.php';
  include_once dirname(__DIR__, 1) . '/src/models/comment.php';

  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');

  $comment = new Comment();
  $comment->set('content',$_GET['content']);
  $comment->set('owner', SessionHelper::getUser()->get('id'));
  $comment->set('post', $_GET['post_id']);
  $comment->set('created', date("Y-m-d H:i:s"));
  $comment->saveNewToDB();
  
  RedirectHelper::redirectFull($_SERVER['HTTP_REFERER']);
?>