<?php




include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['signup'])) {
        $userName = trim($_POST['userName']);
// $lastName = trim($_POST['lName']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);
        $password = md5($password);


    

// Validate phone format
        if (!preg_match("/^[0-9]{11}$/", $phone)) {
            die("Invalid phone number format.");
        }

// Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkEmail);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // echo "Email Address Already Exists!";

            echo "<script>
                alert('Email address already exists!');
                window.location.href = 'index.php';
            </script>";
        } else {

            $activation_token = bin2hex(random_bytes(16));

            $activation_token_hash =hash("sha256", $activation_token);

            $insertQuery = "INSERT INTO users (userName, email, phone, password, account_activation_hash) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $userName, $email, $phone, $password, $activation_token_hash );

            if ($stmt->execute()) {

                $mail = require __DIR__ . "/mailer.php";

                $mail->setFrom("noreply@example.com");
                $mail->addAddress($_POST["email"]);
                $mail->Subject = "Account Activation";
                $mail->Body = <<<END

                Click <a href="http://localhost/login/activate-account.php?token=$activation_token">here</a>
                to activate your account.


                END;

                try {
                    $mail->send();

                }catch (Exception $e) {

                    echo "Message could not be sent. Mailer error:  {$mail->ErrorInfo}";
                    exit;
                }

                echo "
                <script>
                    
                    window.location.href = 'signup-success.html';

                </script>";
                // alert('Signup Successful!');
                // header("Location: index.php");
                // exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }

 
    // if (isset($_POST['signIn'])) {
    //     $email = trim($_POST['email']);
    //     $password = md5($_POST['loginPassword']);

    //     $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("ss", $email, $password);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if ($result->num_rows > 0) {
    //         session_start();
    //         $row = $result->fetch_assoc();
    //         $_SESSION['email'] = $row['email'];
            
    //         header("Location: homepage2.php");
    //         echo "Registered Successfully!";
    //         exit();
    //     } else {
    //         echo "Not Found, Incorrect Email or Password";
    //     }

    //     $stmt->close();
    // }

    if (isset($_POST['signIn'])) {
        $email = trim($_POST['email']);
        $password = md5($_POST['loginPassword']);

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if account is activated
            if (is_null($row['account_activation_hash'])) {
                session_start();
                $_SESSION['email'] = $row['email'];

                // ✅ Redirect to homepage normally
                header("Location: homepage2.php");
                exit();

            } else {
                // Not activated
                header("Location: need_activation.php");
                exit();
            }

        } else {
            // Invalid credentials
            echo "<script>
                alert('Account Not Found, Incorrect Email or Password!');
                // ✅ Clear form before redirecting back to login
                if (window.history.replaceState) {
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
                // Redirect to login page
                window.location.href = 'index.php';
            </script>";
            exit();
        }


        $stmt->close();
    }



    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <h1 class="emailerror">echo</h1> -->
</body>
</html>


