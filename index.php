<?php
//db connection
$dsn = "mysql:host=localhost;port=3306;user=root;password=;dbname=projekts_iesmins;charset=utf8mb4";
$pdo = new PDO($dsn);
//var_dump($pdo);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_delete'])) {
    $userId = $_POST['delete_user'];
    $stmt = $pdo->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->execute([$userId]);
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM accounts");
$stmt->execute();
$userList = $stmt->fetchAll(PDO::FETCH_ASSOC);


//api prieks 6 sunas
$data = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=57.3110&longitude=25.2690&current_weather=true", true);
$weather = json_decode($data, true);
//var_dump($weather);

$code = $weather['current_weather']['weathercode'];


require_once 'functions.php';
$sky = weatherCodeToText($code);
?>

<!DOCTYPE html>
<html lang="en" class="html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="node.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="table">
            <div class="cell" id="cell1">
                <div class="image-wrapper">
                    <img src="img/vtdt.jpg" alt="VTDT">
                    <div class="image-cover"></div>
                </div>
            </div>
            <div class="cell" id="cell2confetti"></div>
            <div class="cell" id="cell3">
                <button onclick="rotate()" id="rotate">Click for a Suprise!</button>
            </div>
            <div class="cell" id="cell4">
                <div class="orb"></div>
            </div>
            <div class="cell" id="cell5">
                <a href="https://youtube.com">YouTube</a><br>
                <a href="https://eklase.lv">E-klase</a><br>
                <a href="https://gmail.com">Gmail</a><br>
                <a href="https://vtdt.lv">VTDT</a><br>
                <a href="https://1a.lv">1A</a>
            </div>
            <div class="cell" id="cell6">
                <div class="weather-box">
                    <div class="location">Latvija, Cēsis</div>

                    <div class="info">
                        <div>Temperatūra: <?php echo $weather['current_weather']['temperature']; ?> °C</div>
                        <div>Laika apstākļi: <?php echo $sky; ?></div>
                    </div>
                </div>
            </div>
            <div class="cell" id="cell7">
                <form id="adduser" method="POST" action="adduser.php">
                    <input type="text" name="firstname" placeholder="First Name" required><br>
                    <input type="text" name="lastname" placeholder="Last Name" required><br>
                    <input type="number" name="phonenumber" placeholder="Phone Number" required><br>
                    <input type="text" name="personalcode" placeholder="Personal Code" required><br>
                    <input type="hidden" name="adduser" value="1">
                    <button type="submit">Add user</button>
                </form>
            </div>
            <div class="cell" id="cell8">
                <form method="GET" action="edit.php">
                    <select name="id" required>
                        <option value="">Select a user to edit</option>
                        <?php foreach ($userList as $user) { ?>
                            <option value="<?= $user['id'] ?>">
                                <?= $user['name'] . ' ' . $user['last_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="submit">Edit</button>
                </form>
            </div>
            <div class="cell" id="cell9">
                <form method="POST">
                    <select name="delete_user">
                        <?php foreach ($userList as $user) { ?>
                            <option value="<?= $user['id'] ?>">
                                <?= $user['name'] . ' ' . $user['last_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button name="submit_delete">Delete</button>

                </form>
            </div>
        </div>
    </div>
</body>

</html>