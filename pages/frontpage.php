<?php
  /** 
   * Implements a webserver program that handles the frontpage functionality.
   */
  include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
  include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
  include_once dirname(__DIR__, 1) . '/src/motdhandler.php';

  if (!SessionHelper::isLoggedIn())
    RedirectHelper::redirect('index.php');

  $html = file_get_contents('../html/frontpage.html');
  $html = str_replace('---$gotosubfredditform---', file_get_contents('../html/components/gotosubfredditform.html'), $html);
  $MOTDHandler = new MOTDHandler();
  $motd = $MOTDHandler->generate();
  $html = str_replace('---$motd---', $motd, $html);

  if (isset($_COOKIE['lastlogin'])) {
    $html = str_replace('---$lastlogin---', $_COOKIE['lastlogin'], $html);
  } else {
    $html = str_replace('---$lastlogin---', '', $html);
  }

  echo $html;
?>