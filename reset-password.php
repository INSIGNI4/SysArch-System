
<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/connect.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <title>Reset Password</title>
</head>
<body>

    <div class="container3" style="border: 1.5px solid black; height:350px; width: 400px; border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.9);" >

        <h1 style="text-align: center; margin-top: 20px;">Reset Password</h1>

        <form action="process-reset-password.php" method="post" id="registrationForm">

            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="input-group" style="padding: 0 40px; margin-top: 20px;">
                <label for="password"></label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="  Password" required autocomplete="off">
                    <button type="button" class="toggle-icon" id="registerPasswordToggle" onclick="toggleRegisterPassword()">👁️</button>
                    
                </div>
                <div class="validation" id="passwordValidation" style="font-weight: bolder; "></div>
            </div>

            <div class="input-group" style="padding: 0 40px; margin-top: 20px;">
                <i class="fas fa-lock"></i>
                <label for="confirm_password"></label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="  Confirm Password" required autocomplete="off">
                <button type="button" class="toggle-icon" onclick="toggleRegisterPassword()"></button>
                <div class="validation" id="confirmPasswordValidation"style="font-weight: bolder;"></div> 

                <button class="btn" style="margin-top: 20px; text-decoration: none;">SEND</button>        
            </div>


            
        </form>
        <form action="index.php" method="post" >
            <div class="links">
                <button id="signInButton" style="background: transparent; color:red;font-weight: bolder;margin-top:-10px; ">Return to Login </button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('registrationForm');
        const passwordInput = document.getElementById('password');
        const validationMsg = document.getElementById('passwordValidation');
        const body = document.getElementById('container0');





        function validatePassword(password) {
            const lengthCheck = password.length >= 16;
            const lowercaseCheck = /[a-z]/.test(password);
            const uppercaseCheck = /[A-Z]/.test(password);
            const numberCheck = /[0-9]/.test(password);
            const specialCharCheck = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            return lengthCheck && lowercaseCheck && uppercaseCheck && numberCheck && specialCharCheck;
        }

        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            if (!validatePassword(password)) {
                validationMsg.textContent = "Password must be at least 16 characters and include uppercase, lowercase, number, and special symbol.";
                
            } else {
                validationMsg.textContent = "";
            }
        });

        // form.addEventListener('submit', (e) => {
        //     const password = passwordInput.value;
        //     if (!validatePassword(password)) {
        //         e.preventDefault();
        //         alert("Please enter a valid password.");
        //     }
        // });

        form.addEventListener('submit', (e) => {
            const password = passwordInput.value;
            const confirm = document.getElementById("confirm_password").value;

            if (!validatePassword(password)) {
                e.preventDefault();
                alert("Please enter a valid password.");
            } else if (password !== confirm) {
                e.preventDefault();
                alert("Passwords do not match.");
            }
        });


        const confirmInput = document.getElementById("confirm_password");
        const confirmationMsg = document.getElementById("confirmPasswordValidation");

        confirmInput.addEventListener('input', () => {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (password !== confirm) {
                confirmationMsg.textContent = "Passwords do not match.";
            } else {
                confirmationMsg.textContent = "";
            }
        });


        function toggleLoginPassword() {
            const input3 = document.getElementById("loginPassword");


            if (input3.type === "password") {
                input3.type = "text";

            } else {
                input3.type = "password";

            }
        }


        function toggleRegisterPassword() {
            const input = document.getElementById("password");
            const input2 = document.getElementById("confirm_password");


            if (input.type === "password") {
                input.type = "text";
                input2.type = "text";

            } else {
                input.type = "password";
                input2.type = "password";

            }



        }


    </script>
<script>
window.addEventListener("load", function() {
  document.body.classList.add("loaded");
});
</script>

</body>
</html>