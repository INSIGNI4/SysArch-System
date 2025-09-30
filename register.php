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
            echo "Email Address Already Exists!";
        } else {
// Insert new user
            $insertQuery = "INSERT INTO users (userName, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $userName, $email, $phone, $password);

            if ($stmt->execute()) {

                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }

 
    if (isset($_POST['signIn'])) {
        $email = trim($_POST['email']);
        $password = md5($_POST['loginPassword']);

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            
            header("Location: homepage2.php");
            echo "Registered Successfully!";
            exit();
        } else {
            echo "Not Found, Incorrect Email or Password";
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
    <h1 class="emailerror">echo</h1>
</body>
</html>


