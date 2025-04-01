<?php
session_start();
if (isset($_SESSION['username'])) {
  if ($_SESSION['user_role'] != 'admin') {
    header('Location: user.php');
    exit;
  }
} else {
  header('Location: login.html');
  exit;
}
include '../../config/database.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM requests WHERE status = 'pending'";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/assets/css/styles.css">
  <title>Document</title>
</head>

<body>
  <a href="../../auth/logout.php">Logout</a>
  <h1 style="text-align: center;">Admin Dashboard</h1>
  <form class="filterContainer" style="display: flex; justify-content: center; align-items: center; gap: 10px; padding: 60px 0px 40px 0px;">
    <span style="font-weight: bold; font-size: large">Category:  </span>
    <select name="" id="">
      <option value="it support">IT Support</option>
      <option value="maintenance">Maintenance</option>
      <option value="admin tasks">Admin Tasks</option>
    </select>
    <button id="filterButton" style="background-color: rgba(28, 205, 122, 0.8); color:white; font-weight: bold; padding: 10px 20px; border: none; font-size: medium; cursor: pointer">filter</button>
  </form>
  <h2 style="text-align: center;">Requests</h2>
</body>
<div class="requestsContainer" id="content">
  <?php
  while ($row = mysqli_fetch_assoc($result)) {

    echo "<div class=\"request\">";
    echo "<h3>Request: {$row['title']}</h3>";
    echo "<p>{$row['description']}</p>";
    echo "<h3>Reply:</h3>";
    $id = $row['id'];
    $sql1 = "SELECT * FROM responses WHERE request_id = '$id'";
    $result1 = mysqli_query($db, $sql1);
    if (mysqli_num_rows($result1) == 0) {
      echo "<form action=\"../models/response.php?id={$row['id']}\" method=\"POST\">
      <input name=\"response\" class=\"responseInput\" type=\"text\" required>
      <button class=\"responseButton\" type=\"submit\">Reply</button>
      </form>";
    } else {
      $row1 = mysqli_fetch_assoc($result1);
      echo "<p>{$row1['response_message']}</p>";
    }

    echo "<div class=\"buttonContainer\">
          <a href=\"../models/solved.php?id={$row['id']}\">Solved</a>
        </div>";
    echo "<span class=\"status\">{$row['status']}</span>";
    echo "</div>";
  }
  ?>
</div>

</html>