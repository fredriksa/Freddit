<?php
  /** Implements a webserver program that displays a form for retrieving emails.
   */
  header('Content-type: text/html');
  include_once('./src/sessionhelper.php');
  include_once('./src/redirecthelper.php');
  include_once('./src/model.php');
  include_once('./src/datamapstore.php');

  DataMapStore::getInstance();

  if (SessionHelper::isLoggedIn())
    RedirectHelper::redirect('pages/frontpage.php');
  $html = file_get_contents('./html/index.html');
  
  $authenticateForm = file_get_contents('./html/components/authenticateform.html');
  $html = str_replace('---$authenticateForm---', $authenticateForm, $html);
  
  $msg = isset($_GET['message']) ? $_GET['message'] : '';
  $html = str_replace('---$statusMessage---', $msg, $html);

  echo $html;
?>