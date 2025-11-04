const signUpButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpForm=document.getElementById('signup');
const form = document.getElementById('registrationForm');
const passwordInput = document.getElementById('password');
const validationMsg = document.getElementById('passwordValidation');
const body = document.getElementById('container0');


// --- FADE IN/OUT TRANSITION HANDLER ---
signUpButton.addEventListener('click', function () {
    // Add fade-out to login
    signInForm.classList.add('fade-out');

    setTimeout(() => {
        signInForm.style.display = "none";
        signUpForm.style.display = "flex";
        signUpForm.classList.add('active'); // fade in signup
        body.style.backgroundImage = "url('registrationBG.png')";
    }, 300); // delay to match CSS transition
});

signInButton.addEventListener('click', function () {
    // fade out signup
    signUpForm.classList.remove('active');

    setTimeout(() => {
        signUpForm.style.display = "none";
        signInForm.style.display = "block";
        signInForm.classList.remove('fade-out'); // show login again
        body.style.backgroundImage = "url('bglogin.png')";
    }, 300);
});


// signUpButton.addEventListener('click',function(){
//     signInForm.style.display="none";
//     signUpForm.style.display="block";
//     body.style.backgroundImage = "url('registrationBG.png')";

// })

// signInButton.addEventListener('click',function(){
//     signInForm.style.display="block";
//     signUpForm.style.display="none";
//     body.style.backgroundImage = "url('bglogin.png')";
// })



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









