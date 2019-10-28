<?php
  /**
   * Implements a route that makes it possible for a user to logout if logged in.
   * Redirects back to the index page.
   */
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  
  session_start();
  if (SessionHelper::isLoggedIn())
    SessionHelper::loggout();

  RedirectHelper::redirect('/index.php');
?>