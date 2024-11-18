<?php
  require_once('conn.php');

  if (empty($_POST['username'])) {
    die('請輸入 username');
  }
  $username = $_POST['username'];
  $sql = sprintf(
    'insert into users(username) values("%s")',
    $username
  );
  $result = $conn->query($sql);
  if (!$result) {
    die($conn->error);
  }

  // 如果有新增成功
  header('Location: index.php');
?>