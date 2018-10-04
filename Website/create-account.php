<?php
    require('config.php');
?>
<!DOCTYPE html>
<!--

I did not write all of this code, please see create-account.html for full author history.

I added lines:
1-4
68-75

I modified lines:
82
86

-->
<html>
    <head>
        <title>HARP Login</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        /*window.onload = function(){
            var accountBehavior = getParameterByName("accountBehavior");
            
            if (accountBehavior == "alreadyExists")
            {
                fnSetNewAccountBehavior("This username already exists. Please choose a new one.")
            }
            else if (accountBehavior == "strongerPassword")
            {
                fnSetNewAccountBehavior("Your password must be between 8-30 characters.");
            }
            else if (accountBehavior == "notEmail")
            {
                fnSetNewAccountBehavior("Please enter an actual email address.");
            }
        };*/
        
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        
        function fnSetNewAccountBehavior(arg) {
            var errorText = document.getElementById("accountErrorText");
            if (errorText.classList.contains('lbl-create-account-hidden'))
            {
                errorText.innerHTML = arg;
                errorText.classList.remove('lbl-create-account-hidden');
                errorText.classList.add('lbl-create-account-visible');
            }
        }
        
        //username validation
        $(function() { 
            $('#username').on('keyup', function () {
              if ($('#username').val().length > 0) {
                if(($('#password').val() == $('#confirm_password').val()) && ($('#password').val().length != 0) && ($('#confirm_password').val().length != 0)) {
                    $('#login_btn').removeAttr('disabled');
                    $('#login_btn').removeClass('btn-create-disabled');
                    $('#login_btn').addClass('btn-create-enabled');
                }
              } else {
                $('#login_btn').attr('disabled','disabled');
                $('#login_btn').removeClass('btn-create-enabled');
                $('#login_btn').addClass('btn-create-disabled');
              }
            });
        });
        
        //password validation
        $(function() { 
            $('#password').on('keyup', function () {
              if ($('#password').val().length >= 8) {
                if(($('#password').val() == $('#confirm_password').val()) && ($('#password').val().length != 0) && ($('#confirm_password').val().length != 0)) {
                    $('#login_btn').removeAttr('disabled');
                    $('#login_btn').removeClass('btn-create-disabled');
                    $('#login_btn').addClass('btn-create-enabled');
                }
                $('#accountErrorText').html('');
                $('#password_check').css('color', 'green').removeClass('fa-lock').removeClass('fa-times').addClass('fa-check');
              } else {
                $('#login_btn').attr('disabled','disabled');
                $('#login_btn').removeClass('btn-create-enabled');
                $('#login_btn').addClass('btn-create-disabled');
                $('#accountErrorText').removeClass('lbl-create-account-hidden').addClass('lbl-create-account-visible').html('The password must be at least 8 characters long.');
                $('#password_check').css('color', 'red').removeClass('fa-lock').removeClass('fa-check').addClass('fa-times');
              }
            });
        });
        
        //confirmed password validation
        $(function() { 
            $('#password, #confirm_password').on('keyup', function () {
                if($('#confirm_password').val().length != 0) {
                  if (($('#password').val() == $('#confirm_password').val()) && ($('#password').val().length != 0) && ($('#confirm_password').val().length >= 8)) {
                      if($('#username').val().length != 0) {
                    $('#login_btn').removeAttr('disabled');
                    $('#login_btn').removeClass('btn-create-disabled');
                    $('#login_btn').addClass('btn-create-enabled');
                  }
                    $('#accountErrorText').html('');
                    $('#confirm_password_check').css('color', 'green').removeClass('fa-lock').removeClass('fa-times').addClass('fa-check');
                  } else {
                    $('#login_btn').attr('disabled','disabled');
                    $('#login_btn').removeClass('btn-create-enabled');
                    $('#login_btn').addClass('btn-create-disabled');
                    $('#confirm_password_check').css('color', 'red').removeClass('fa-lock').removeClass('fa-check').addClass('fa-times');
                    $('#accountErrorText').removeClass('lbl-create-account-hidden').addClass('lbl-create-account-visible').html('The password fields do not match.');
                  }
                }
            });
        });
    </script>
    
    <body class="login-body">
        <div class="create-account-form-container">
            <form action="createAccountScript.php" method="post">
                <h1 class="text-center h1-main-header">New Account</h1>
                <?php
                    if (!isset($_SESSION['createMsg'])) {
                        echo '<label id="accountErrorText" class="lbl-create-account-hidden"></label>';
                    } else { 
                        echo '<label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['createMsg'] . '</label>';
                        unset($_SESSION['createMsg']);
                    }
                ?>
                <div class="col-md-12">
                    <i class="fa fa-user fa-login"></i>
                    <input id="username" type="text" placeholder="Username" class="input-login" required name="uname">
                </div>
                <div class="col-md-12">
                    <i id="password_check" class="fa fa-lock fa-login"></i>
                    <input id="password" type="password" placeholder="Password" class="input-login" required name="passw1">
                </div>
                <div class="col-md-12">
                    <i id="confirm_password_check" class="fa fa-lock fa-login"></i>
                    <input id="confirm_password" type="password" placeholder="Confirm Password" class="input-login" required name="passw2">
                </div>
                <span id="message"></span>
                <div class="btn-login-float">
                    <button id="login_btn" class="btn-create btn-create-disabled" type="submit" disabled>Create</button>
                </div>
            </form>
        </div>
    </body>    
</html>