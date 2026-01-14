<?php
session_start();
//db connection
$dsn = "mysql:host=localhost;port=3306;user=root;password=;dbname=projekts_iesmins;charset=utf8mb4";
$pdo = new PDO($dsn);

$userId = $_GET['id'] ?? null;
$user = null;
$errors = [];

if ($userId) {
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $phonenumber = trim($_POST['phonenumber'] ?? '');
    $personalcode = trim($_POST['personalcode'] ?? '');

    if (!preg_match('/^[\p{L}\s]+$/u', $firstname))
        $errors[] = "First name can only contain letters and spaces.";
    if (!preg_match('/^[\p{L}\s]+$/u', $lastname))
        $errors[] = "Last name can only contain letters and spaces.";
    if (!preg_match('/^[0-9\-]+$/', $personalcode))
        $errors[] = "Personal code can only contain digits and dashes.";
    if (strlen($phonenumber) != 8)
        $errors[] = "Phone number must be 8 digits long.";
    if (strlen($personalcode) != 12)
        $errors[] = "Personal code must be 12 digits. Check if you separated it with a dash.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE accounts SET name = ?, last_name = ?, phone = ?, pcode = ? WHERE id = ?");
        if ($stmt->execute([$firstname, $lastname, $phonenumber, $personalcode, $userId])) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "DB error: " . implode(", ", $stmt->errorInfo());
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<style>
    .container {
        display: flex;
        height: 100vw;
        width: 100vw;
        overflow: hidden;
        justify-content: center;
    }

    form {
        padding-top: 30vh;
    }

    input {
        width: 250px;
        height: 50px;
        text-align: center;
        font-size: 1.5rem;
        margin: 5px;
    }

    button {
        width: 250px;
        height: 50px;
        margin: 5px;
        font-size: 1.5rem;
    }
</style>

<body class="container">
    <?php if ($user): ?>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form id="edituser" method="POST">
            <input type="text" name="firstname" placeholder="First Name" value="<?= htmlspecialchars($user['NAME']) ?>"
                required><br>
            <input type="text" name="lastname" placeholder="Last Name" value="<?= htmlspecialchars($user['last_name']) ?>"
                required><br>
            <input type="number" name="phonenumber" placeholder="Phone Number"
                value="<?= htmlspecialchars($user['phone']) ?>" required><br>
            <input type="text" name="personalcode" placeholder="Personal Code"
                value="<?= htmlspecialchars($user['pcode']) ?>" required><br>
            <button type="submit">Update user</button>
        </form>
    <?php else: ?>
        <p>User not found. Please select a user to edit.</p>
    <?php endif; ?>
</body>

</html>