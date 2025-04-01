<?php
session_start();
if (isset($_SESSION['username'])) {
  if ($_SESSION['user_role'] != 'user') {
    header('Location: admin.php');
    exit;
  }
} else {
  header('Location: login.html');
  exit;
}
include '../models/getUserRequests.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM requests WHERE user_id = '$user_id'";
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
  <h1 class="title">Request Submission Form</h1>
  <form action="../models/addUserRequest.php" method="POST" id="serviceRequestForm">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Category</label>
    <select name="category" required>
      <option value=""></option>
      <option value="it support">IT_Support</option>
      <option value="maintenance">Maintenance</option>
      <option value="admin tasks">Admin_Tasks</option>
    </select>
    <label>Description</label>
    <textarea name="description" required></textarea>
    <button type="submit">Submit</button>
  </form>
  <div style="display: <?php if (mysqli_num_rows($result) > 0) {
                          echo "block";
                        } else {
                          echo "none";
                        } ?>" class="requestsContainer">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<div class=\"request\">";
      echo "<h3>{$row['category']}</h3>";
      echo "<h3>{$row['title']}</h3>";
      echo "<p>{$row['description']}</p>";
      echo "<h3>Reply:</h3>";
      $id = $row['id'];
      $sql1 = "SELECT * FROM responses WHERE request_id = '$id'";
      $result1 = mysqli_query($db, $sql1);
      if (mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_assoc($result1);
        echo "<p>{$row1['response_message']}</p>";
      }
      echo "<div class=\"buttonContainer\">
        <a href=\"../models/updateUserRequest.php?id={$row['id']}\">Update</a>
        <a href=\"../models/deleteUserRequest.php?id={$row['id']}\">Delete</a>
      </div>";
      echo "<span class=\"status\">{$row['status']}</span>";
      echo "</div>";
    }
    ?>
  </div>
  <script src="../../public/assets/js/script.js"></script>
</body>

</html>