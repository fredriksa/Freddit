<?php
/**
 * Implements a route that allows for users to send messages to eachother on Freddit.
 */
include_once dirname(__DIR__, 1) . '/src/redirecthelper.php';
include_once dirname(__DIR__, 1) . '/src/sessionhelper.php';
include_once dirname(__DIR__, 1) . '/src/emailhelper.php';

if (!SessionHelper::isLoggedIn())
  RedirectHelper::redirect('/pages/frontpage.php');

EmailHelper::sendEmail($_GET['target'], $_GET['subject'], $_GET['content']);
RedirectHelper::redirect('pages/privatemessaging.php');
?>