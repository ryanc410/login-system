$(document).ready(function(){
    // Validation for Username
    $("#username").on("input", function(){
        var username = $(this).val();
        var validUsername = /^([a-zA-Z0-9_]{1,25})$/;

        if (username.length == 0){
            $(".username-msg").addClass("invalid").text("Username cannot be blank.");
            $(this).addClass("invalid-input").removeClass("valid-input");
            $("#submit-btn").attr("disabled", "disabled");
        } else if (!validUsername.test(username)){
            $(".username-msg").addClass("invalid").text("Valid characters for a Username include, a-z, A-Z, 0-9 and underscores.");
            $(this).addClass("invalid-input").removeClass("valid-input");
            $("#submit-btn").attr("disabled", "disabled");
        } else {
            $(".username-msg").empty();
            $(this).addClass("valid-input").removeClass("invalid-input");
        }
    });
    // Validation for Email Address
    $('#email').on('input', function(){
        var email = $(this).val();
        var validEmail = /^([a-zA-Z0-9_]{1,75})@{1}([a-zA-Z0-9]{1,50})\.{1}((\bcom\b)?(\bnet\b)?(\borg\b)?){1}$/;

        if (email.length == 0){
            $(".email-msg").addClass("invalid").text("Email Address cannot be blank.");
            $(this).addClass("invalid-input").removeClass("valid-input");
        }else if (!validEmail.test(email)){
            $(".email-msg").addClass("invalid").text("Invalid Email address format.");
            $(this).addClass("invalid-input").removeClass("valid-input");
        } else {
            $('.email-msg').empty();
            $(this).addClass('valid-input').removeClass('invalid-input');
        }
    });
    // Validation for Password
    $("#password").on("input", function(){
        var pass = $(this).val();
        var validPass = /^[a-zA-Z0-9]{8,}$/;
        if (pass.length == 0){
            $(".password-msg").addClass("invalid").text("You must enter a password");
            $(this).addClass("invalid-input").removeClass("valid-input");
        } else if (!validPass.test(pass)){
            $(".password-msg").addClass("invalid").text("A-Z, a-z, and 0-9 and minimum 8 characters");
            $(this).addClass("invalid-input").removeClass("valid-input");
        } else {
            $(".password-msg").empty();
            $(this).addClass("valid-input").removeClass("invalid-input");
        }
    });
// Confirm Password Validation
    $("#confirm_password").on("input", function(){
        var cpass = $(this).val();
        var pass = $("#password").val();
        if (cpass.length == 0){
            $(".cpassword-msg").addClass("invalid").text("You must confirm your password");
            $(this).addClass("invalid-input").removeClass("valid-input");
        } else if (cpass !== pass){
            $(".cpassword-msg").addClass("invalid").text("Passwords do not match");
            $(this).addClass("invalid-input").removeClass("valid-input");
        } else {
            $(".cpassword-msg").empty();
            $(this).addClass("valid-input").removeClass("invalid-input");
        }
    });
    // Check Errors and Submit form if none
    $("input").on("input", function(e){
        if($("#registerForm").find(".valid-input").length==4){
            $("#submit-btn").removeClass("deny-submit");
            $("#submit-btn").prop("disabled", false);
        } else {
            e.preventDefault();
            $("#submit-btn").prop("disabled", true);
        }
    });
})
