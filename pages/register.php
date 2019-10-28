<?php
  /** 
   * Implements a webserver program that handles registration for the user.
   */
  header('Content-type: text/html');
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';

  if (SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');
    
  $html = file_get_contents('../html/register.html');

  $message = isset($_GET['message']) ? $_GET['message'] : '';
  $html = str_replace('---$message---', $message, $html);
  echo $html;
?>