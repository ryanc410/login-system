# PHP Registration and Login System
This project adds login and signup functionality to your website. Bootstrap 5 is used for the front end, Jquery for form validation, and PHP for backend form-validation and running queries to the Database. 

## Requirements
1. Mariadb or Mysql server
2. Git
3. PHP >=7.4
4. PHP extensions: libapache2-mod-php, php-mysql
5. Apache Web server. You can technically use any web server you like, but I will be explaining how to install using Apache as the Web Server.

# Installation

This guide assumes you have a Web Server already configured to serve your site files as well as have all the required Apps installed already.

---

## Clone the Repository

This clones the repository and moves all the files to the default DocumentRoot location. You can change the location to suit your setup.
````bash
git clone https://github.com/ryanc410/login-system.git
mv login-system/* /var/www/html/
````

---

## Database Configuration

````bash
sudo mysql -u root -pPASSWORD
CREATE DATABASE login;
CREATE USER 'login'@'localhost' IDENTIFIED BY 'PASSWORDHERE';
GRANT ALL PRIVILEGES ON login.* TO 'login'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
USE login;
CREATE TABLE `users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`full_name` VARCHAR(50) NOT NULL,
	`email` VARCHAR(150) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`date_created` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	UNIQUE KEY `email` (`email`) USING BTREE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;
EXIT;
````

Nevigate to your Webroot directory and open the `includes/db_connect.php` file

Change the following lines to the information you created in the previous step.
````bash
define('DB_USERNAME', 'login');
define('DB_PASSWORD', 'DATABASE_USER_PASSWORD');
define('DB_NAME', 'login');
````

---

## PHP Configuration

In order for the include files to work, you must configure the directory in the php.ini file. 

Move the includes folder outside of the webroot directory and set the correct permissions.
````bash
sudo mv /var/www/html/includes /var/www/
sudo chown www-data:www-data /var/www/includes
````

Open the file `/etc/php/*.*/apache2/php.ini` and find line 740.

````bash
;include_path = ".:/php/includes"
````

Change to:

````bash
include_path = "/var/www/includes"
````

Save and Exit the file.
Restart the Apache web server for the changes to take effect.

````bash
sudo systemctl restart apache2
````

---

## Logged in User Access Only

<br>

For any page that you want to be accessible only by logged in users, you must add the following snippet at the top of the page:

````php
<?php

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
} else {
    // If user logged in..
}
````

---

## Adding New input fields in Registration Form

<br>

Server side validation in the PHP block above the registration form.

`register.php`
````php
    if (empty(test_input($_POST["newInputField"]))) {
		$newInputFieldErr = "ERROR MESSAGE GOES HERE";
	} elseif (!preg_match('REGEX GOES HERE THAT MATCHES INPUT', test_input($_POST["newInputField"]))) {
		$newInputFieldErr = "ERROR MESSAGE IF FIELD DOESNT MATCH REGEX";
	} else {
        $newInputField = test_input($_POST['newInputField']);
    }
````

Add this to the signup form replacing the `newInputField` with the name of the new field.

`register.php`
````html
<div class="mb-3">
    <label for="newInputField" class="mb-1">New Input Field</label>
    <input type="text" class="form-control" name="newInputField" id="newInputField" />
    <span class="invalid-feedback">
        <?php if($newInputFieldErr){
            echo $newInputFieldErr;
        }
        ?>
    </span>
    <div class="new-input-field-msg"></div>
````

<br><br>

#========================================================
Need to add insert query for newInputField!
#========================================================

<br><br>

`js/form-validation.js`

````javascript
$("#newInputField).on("input",function(){
    var newInput = $(this).val();
    var newInputRegex = //;

    if(newInput.length === 0){
        $(this).addClass("invalid").removeClass("valid");
        $(".new-input-field-msg).addClass("error").text("This field cannot be empty.");
    } else if (!newInputRegex.test(newInput)){
        $(this).addClass("invalid").removeClass("valid");
        $(".new-input-field-msg).addClass("error").text("Invalid New Input Field.");
    } else {
        $(".new-input-field-msg).empty();
        $(this).addClass("valid").removeClass("invalid");
    }
});
````

For every new Input field you add, increase this value by one. You can find this on line 87.

`js/form-validation.js`
````javascript
    if(valid.length === 4){
````
