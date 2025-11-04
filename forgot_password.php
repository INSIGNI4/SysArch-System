<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>
    
    <div class="container3" style="border: 1.5px solid black; height:250px; width: 400px; border-radius: 20px; background-color: rgba(255, 255, 255, 0.8); ">

        <h1 style="text-align: center; margin-top: 20px;">Forgot Password</h1>
        <form action="send-password-reset.php" method="post">
            <div class="input-group"style="padding: 0 40px; margin-top: 20px">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="  Email"required autocomplete="off">
                <label for="email"></label>
                <button class="btn" style="margin-top: 20px; text-decoration: none;" >SEND</button>
                <a class="fas fa-angle-left" href="index.php" style="margin-top: 30px; width: 100%; background:linear-gradient(to top,rgb(0, 0, 0),rgb(85, 78, 79)); 
                color: white; padding: 5px; text-decoration: none;"></a>
            </div>
            
        </form>
    </div>
    <script src="script.js"></script>
<script>
window.addEventListener("load", function() {
  document.body.classList.add("loaded");
});
</script>
    
</body>
</html>