
<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    
</head>
<body id="container0">

    <div class="container2" id="signup" style="display:none; background-color: rgba(109, 101, 101, 0.1);">
        <h1 class="form-title"></h1>
        <form method="post" action="register.php" id="registrationForm">
            <div class="container4">  
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="userName" id="userName" placeholder="  Username"required autocomplete="off">
                    <label for="userName"></label>
                </div>
                <div class="input-group">
                    <label for="password"></label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="  Password" required autocomplete="off">
                        <button type="button" class="toggle-icon" id="registerPasswordToggle" onclick="toggleRegisterPassword()">👁️</button>
                    </div>
                    <div class="validation" id="passwordValidation" style="font-weight: bolder;background-color: rgba(255, 255, 255, 0.9); margin-top: 2px;border: 2px solid red; text-align:center;"></div>
                </div>
                <div class="input-group ">
                    <i class="fas fa-lock"></i>
                    <label for="confirm_password"></label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="  Confirm Password"required autocomplete="off">
                    <button type="button" class="toggle-icon" onclick="toggleRegisterPassword()"></button>
                    <div class="validation" id="confirmPasswordValidation"style="font-weight: bolder;background-color: rgba(255, 255, 255, 0.9); margin-top: 2px;border: 2px solid red; text-align:center;"></div> 
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="  Email"required autocomplete="off">
                    <label for="email"></label>                
                </div>
                <div class="input-group ">
                    <i class="fas fa-phone"></i>
                    <input type="tel" name="phone" id="phone" placeholder="  Phone Nmuber (e.g. 0912345678)" required pattern="[0-9]{11}" maxlength=11 autocomplete="off">
                    <label for="phone_number"></label>
                    <div class="submit" id="Register"></div>
                </div>
                <!-- <div class="input-group ">
                    <div class="user-type-container">
                        <select><option>User Type...</option><option>Standard</option><option>Admin</option></select>
                        <input type="text" placeholder="Code (ADMIN)">
                    </div>
                </div> -->
            </div>  
            <div class="container5">  
                <div class="terms-agreement" style="margin-top: 10px; background: transparent;">
                    <h3 style="text-align: center; margin-bottom: 10px">Inventory Management Agreement</h3>
                    <p style="text-align: center; margin-right: 20px; margin-bottom:230px; text-align: justify;">
                    This Inventory Management Agreement (“Agreement”) is entered into as of the earliest Product Land Date set forth on Schedule A (the “Effective Date”) by and between the vendor identified on Schedule A (“Vendor”), and Forever 8 Fund, LLC, a limited liability company organized and existing under the laws of Delaware (“F8”). Vendor and F8 are sometimes individually referred to herein as a “Party” and collectively as the “Parties.” The owner of Vendor identified on Schedule A (“Owner”) joins this Agreement for the limited purposes described in the Agreement.
                    <br>
                    <br>

                    WHEREAS, Vendor is in the business of selling the product(s) described on Schedule A, (each, a “Product” and, collectively, the “Products”) though the e-commerce or direct-to-consumer platform(s) identified on Schedule A (the “Platform”); and
                    <br>
                    <br>
                    WHEREAS, F8 desires to maintain inventory of and sell to Vendor the Products pursuant to the terms and conditions set forth in this Agreement.
                    </p>


                </div>           
            </div> 
            <div class="terms">
                <input type="checkbox" class="terms_btn"  placeholder="  Terms & Aggrement"required></button>
                <p class="or">Terms & Aggrement</p>
                
            </div> 
            <div class="signup_group">
                <input type="submit" value="Sign Up     " class="btn_signup" name="signup" style="margin-top:10px;">
            </div>
                <div class="links">
                    <p>Already Have Account ?</p>
                    <button id="signInButton" style="background: transparent; color:red ">Return to Login </button>
                </div>


        </form>

    </div>



    <div class="container1" id="signIn">
        <h1 class="form-title"></h1>
        <form method="post" action="register.php" id="loginForm" class="loginForm">
            <div class="input-group ">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="  Email"required autocomplete="off">
                <label for="email"></label>
            </div>
            <div class="input-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="loginPassword" id="loginPassword" placeholder="  Password" required autocomplete="off">
                    <button type="button" class="toggle-icon" id="loginPasswordToggle" onclick="toggleLoginPassword()">👁️</button>
                </div>
            </div>
            <p class="recover">
                <a href="forgot_password.php">Forgot Password ?</a>
            </p>
            <input type="submit" value="LOGIN" class="btn" name="signIn" >
        </form> 
        <div class="links2">
            <input id="signUpButton" type="submit" value="REGISTER" class="btn2" name="signIn" >
            <!-- <button id="signUpButton">Sign Up</button> -->
        </div>
        <div class="pan" id="pan"><pan id="border"></pan></div>  
    </div>
    <script src="script.js"></script>
<script>
window.addEventListener("pageshow", function (event) {
  // If the page was loaded from bfcache (user pressed Back)
  if (event.persisted) {
    // Force a reload so PHP runs and clears everything
    window.location.reload(true);
  }

  // Always clear input fields when login page loads
  const email = document.querySelector('input[name="email"]');
  const password = document.querySelector('input[name="loginPassword"]');
  if (email) email.value = '';
  if (password) password.value = '';
});
</script>

<!-- FOR TRANSITION AFTER LOGOUT -->
<script>
window.addEventListener("load", function() {
  document.body.classList.add("loaded");
});
</script>

</body>
</html>

