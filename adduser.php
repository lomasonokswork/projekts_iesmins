<?php
require_once 'index.php';
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
  }
}
?>