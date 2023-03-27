$(document).ready(function(){
    // Disable the submit button
    $("#signup-submit").prop("disabled", true);
    // Full name validation
    $("#full_name").on("input", function(){
        var name = $(this).val();
        // Matches a first name with max 25 characters, followed by a space and a last name with a max of 25 characters
        var validName = /(^[A-Za-z]{2,25}\s[A-Za-z]{2,25}$)/;
        // If the full_name field is empty, show error
        if(name.length === 0){
            $(this).addClass("invalid").removeClass("valid");
            $(".name-msg").addClass("error").text("Full Name Required");
            // If full_name doesnt match the regex pattern, show error
        } else if (!validName.test(name)){
            $(this).addClass("invalid").removeClass("valid");
            $(".name-msg").addClass("error").text("Alphanumeric characters and spaces only");
        } else {
            $(".name-msg").empty();
            $(this).addClass("valid").removeClass("invalid");
        }
    });
    // Email Address validation
    $("#email").on("input", function(){
        var email = $(this).val();
        // Email regex
        var validEmail = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
        if(email.length === 0){
            $(this).addClass("invalid").removeClass("valid");
            $(".email-msg").addClass("error").text("Valid Email Address Required");
        } else if (!validEmail.test(email)){
            $(this).addClass("invalid").removeClass("valid");
            $(".email-msg").addClass("error").text("Invalid Email Address");
        } else {
            $(".email-msg").empty();
            $(this).addClass("valid").removeClass("invalid");
        }
    });
    // Password validation
    $("#password").on("input", function(){
        var pass = $(this).val();
        // Regex matching uppercase characters
        var upper = /([A-Z])/;
        // Regex matching lower case characters
        var lower = /([a-z])/;
        // Regex matching numbers
        var num = /([0-9])/;
        if(pass.length === 0){
            $(this).addClass("invalid").removeClass("valid");
            $(".password-msg").addClass("error").text("Password Required");
        } else if (!upper.test(pass)){
            $(this).addClass("invalid").removeClass("valid");
            $(".password-msg").addClass("error").text("Password must have one uppercase character");
        } else if (!lower.test(pass)){
            $(this).addClass("invalid").removeClass("valid");
            $(".password-msg").addClass("error").text("Password must have one lowercase character");
        } else if (!num.test(pass)){
            $(this).addClass("invalid").removeClass("valid");
            $(".password-msg").addClass("error").text("Password must have one numeric character");
            // If the password is less than 8 characters long, show error
        } else if (pass.length < 8){
            $(this).addClass("invalid").removeClass("valid");
            $(".password-msg").addClass("error").text("Password must have atleast 8 characters");
        } else {
            $(".password-msg").empty();
            $(this).addClass("valid").removeClass("invalid");
        }
    });
    // Confirm Password validation
    $("#cpassword").on("input", function(){
        var cpassword = $(this).val();
        var password = $("#password").val();
        if(cpassword.length === 0){
            $(this).addClass("invalid").removeClass("valid");
            $(".cpassword-msg").addClass("error").text("You must confirm your password");
            // If the password doesnt match the confirm password, show error
        } else if (cpassword !== password){
            $(this).addClass("invalid").removeClass("valid");
            $(".cpassword-msg").addClass("error").text("Passwords do not match");
        } else {
            $(".cpassword-msg").empty();
            $(this).addClass("valid").removeClass("invalid");
        }
    });
    // After every input event, check for the valid class to be repeated in our 4 input fields.
    $("input").on("input", function(e){
        var valid = $("#signup-form").find(".valid");
        if(valid.length === 4){
            // If all fields are valid, enable the submit button
            $("#signup-submit").prop("disabled", false);
        } else {
            // If one of the fields is invalid, prevent form submission and disable submit button
            e.preventDefault();
            $("#signup-submit").prop("disabled", true);
        }
    });
});
