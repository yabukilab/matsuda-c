<?php
// HTMLエスケープ関数
function h($var) {
  return is_array($var)
    ? array_map('h', $var)
    : htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

$dbServer = getenv('MYSQL_SERVER')    ?: '127.0.0.1';
$dbUser   = getenv('MYSQL_USER')      ?: 'root';
$dbPass   = getenv('MYSQL_PASSWORD')  ?: '';
$dbName   = getenv('MYSQL_DB')        ?: 'piano_lesson';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Can't connect to DB: " . h($e->getMessage());
  exit;
}
