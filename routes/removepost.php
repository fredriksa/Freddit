<?php
  /**
   * Implements a route that makes it possible for a user to remove a post.
   * Redirects back to th esending page.
   */
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/models/user.php';
  include_once dirname(__DIR__, 1) . '/src/models/post.php';
  
  session_start();
  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('/index.php');
  
  $user = SessionHelper::getUser();
  $post = new Post($_GET['post_id']);

  if ($user->isOwnerOf($post))
    $post->delete();

  RedirectHelper::redirectFull($_SERVER['HTTP_REFERER']);
?>