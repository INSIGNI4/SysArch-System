<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/connect.php";

$sql = "SELECT * FROM users
        WHERE account_activation_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

$sql = "UPDATE users
        SET account_activation_hash = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $user["id"]);

$stmt->execute();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>
    
    <div class="container3" style="border: 1.5px solid black; height:250px; width: 400px; border-radius: 20px; background-color: rgba(255, 255, 255, 0.9); ">

        <h1 style="text-align: center; margin-top: 20px;">Account Activated!</h1>



            <div class="input-group" style="padding: 0 40px; margin-top: 20px">
                <h1 style="text-align: center; margin-top: 20px; font-size: 17px;">
                    <!-- Account activated successfully.  -->
                    You can now
                    <!-- <a href="index.php">log in</a>.</p> -->
                </h1>

                <button class="btn"
                        style="margin-top: 20px; text-decoration: none;"
                        onclick="window.open('index.php', '_blank')">
                    log in
                </button>
                <!-- <a class="fas " href="index.php" style="margin-top: 20px; width: 100%; background:linear-gradient(to top,rgb(0, 0, 0),rgb(85, 78, 79)); 
                color: white; padding: 5px; text-decoration: none; font-size: 11px;"> back to Login?</a> -->
            </div>



    </div>
    <script src="script.js"></script>


<script>
window.addEventListener("load", function() {
  document.body.classList.add("loaded");
});
</script>



</body>
</html>