<?php
session_start();
$dsn = "mysql:host=localhost;port=3306;user=root;password=;dbname=projekts_iesmins;charset=utf8mb4";
$pdo = new PDO($dsn);
$errors = [];

if (isset($_POST['adduser']) && $_POST['adduser'] == 1) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $phonenumber = trim($_POST['phonenumber'] ?? '');
    $personalcode = trim($_POST['personalcode'] ?? '');

    if ($firstname === '')
      $errors[] = "Enter your name.";
    if ($lastname === '')
      $errors[] = "Enter your last name.";
    if ($phonenumber === '')
      $errors[] = "Enter your phone number.";
    if ($personalcode === '')
      $errors[] = "Enter your personal code.";
    if (!preg_match('/^[\p{L}\s]+$/u', $firstname))
      $errors[] = "First name can only contain letters and spaces.";
    if (!preg_match('/^[\p{L}\s]+$/u', $lastname))
      $errors[] = "Last name can only contain letters and spaces.";
    if (!preg_match('/^[0-9\-]+$/', $personalcode))
      $errors[] = "Personal code can only contain digits and dashes.";
    if (strlen($phonenumber) != 8)
      $errors[] = "Phone number must be 7 digits long.";
    if (strlen($personalcode) != 12)
      $errors[] = "Personal code must be 12 digits. Check if you separated it with a dash.";

    if (empty($errors)) {
      $stmt = $pdo->prepare("SELECT id FROM accounts WHERE NAME=?");
      $stmt->bindParam(1, $firstname, PDO::PARAM_STR);
      $stmt->execute();
      if ($stmt->fetch()) {
        $errors[] = "Name already registered.";
      } else {
        $stmt = $pdo->prepare("INSERT INTO accounts (name, last_name, phone, pcode) VALUES (?,?,?,?)");
        $stmt->bindParam(1, $firstname, PDO::PARAM_STR);
        $stmt->bindParam(2, $lastname, PDO::PARAM_STR);
        $stmt->bindParam(3, $phonenumber, PDO::PARAM_INT);
        $stmt->bindParam(4, $personalcode, PDO::PARAM_STR);
        if ($stmt->execute()) {
          error_log("User inserted successfully");
          header("Location: index.php");
          exit();
        } else {
          $errors[] = "DB error: " . implode(", ", $stmt->errorInfo());
        }
      }
    }

    // Store errors in session and redirect back
    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      header("Location: index.php");
      exit();
    }
  }
}
?>