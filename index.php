<?php
//db connection
$dsn = "mysql:host=localhost;port=3306;user=root;password=;dbname=projekts_iesmins;charset=utf8mb4";
$pdo = new PDO($dsn);
//var_dump($pdo);


//api prieks 6 sunas
$data = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=57.3110&longitude=25.2690&current_weather=true", true);
$weather = json_decode($data, true);
//var_dump($weather);

$code = $weather['current_weather']['weathercode'];

require_once 'functions.php';
$sky = weatherCodeToText($code);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="table">
            <div class="cell" id="cell1"></div>
            <div class="cell" id="cell2"></div>
            <div class="cell" id="cell3"></div>
            <div class="cell" id="cell4"></div>
            <div class="cell" id="cell5"></div>
            <div class="cell" id="cell6">
                <div class="weather-box">
                    <div class="location">Latvija, Cēsis</div>

                    <div class="info">
                        <div>Temperatūra: <?php echo $weather['current_weather']['temperature'];?> °C</div>
                        <div>Laika apstākļi: <?php echo $sky;?></div>
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
            <div class="cell" id="cell8"></div>
            <div class="cell" id="cell9"></div>
        </div>
    </div>
</body>

</html>