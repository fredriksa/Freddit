<?php
  header('Content-type: image/png');
  include_once dirname(__DIR__, 1) . '/src/logogenerator.php';
  $logoService = new LogoGenerator();
  $logoService->generate(300, 50);
?>