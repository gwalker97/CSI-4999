<?php
    require('config.php');

    if($_SESSION["guest"] == false) {
        $_SESSION['indexMsg'] = "You are already logged in.";
        header("Location: index.php");
        die();
    }
?>
<!--

I (Alex Manaila) did not write all of this code, please see login.html for full author history.

I added lines:
1-9
76-83

I modified lines:
78
86
90

-->
<!DOCTYPE html>
<html>
    <head>
        <title>HARP Login</title>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        window.onload = function(){
            var loginBehavior = getParameterByName("loginBehavior");
            
            if (loginBehavior == "incorrect")
            {
                fnSetLoginBehavior("Username or password is incorrect.")
            }
            else if (loginBehavior == "notExist")
            {
                fnSetLoginBehavior("Username does not exist.");
            }
            else if (loginBehavior == "newAccount")
            {
                fnSetLoginBehavior("Your account has been created!<br />Please login.");
            }
        };
        
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        
        function fnSetLoginBehavior(arg) {
            var errorText = document.getElementById("loginErrorText");
            if (errorText.classList.contains('lbl-login-hidden'))
            {
                errorText.innerHTML = arg;
                errorText.classList.remove('lbl-login-hidden');
                errorText.classList.add('lbl-login-visible');
            }
        }
    </script>
    
    <body class="login-body">
        <div class="login-form-container">
            <form action="loginScript.php" method="post">
                <svg viewBox="0 0 500 500" style="margin-top: 10px; margin-bottom: -270px; letter-spacing: 75px;">
                    <path id="curve" d="M73.2,148.6c4-6.1,65.5-96.8,178.6-95.6c111.3,1.2,170.8,90.3,175.1,97" />
                    <text width="500">
                      <textPath class="h1-login text-center" xlink:href="#curve">
                        HARP
                      </textPath>
                    </text>
                  </svg>
<!--                <h1 class="col-md-12 text-center h1-login">HARP</h1>-->
                <img src="Images/harp_icon.png" class="harp-img">
                <h4 class="col-md-12 text-center h4-login">Home Automation: Raspberry Pi</h4>
                <?php
                    if (!isset($_SESSION['loginMsg'])) {
                        echo '<label id="loginErrorText" class="lbl-login-hidden"></label>';
                    } else { 
                        echo '<label id="loginErrorText" class="lbl-login-visible">' . $_SESSION['loginMsg'] . '</label>';
                        unset($_SESSION['loginMsg']);
                    }
                ?>
                <div class="col-md-12">
                    <i class="fa fa-user fa-login"></i>
                    <input type="text" placeholder="Username" class="input-login" required name="uname">
                </div>
                <div class="col-md-12">
                    <i class="fa fa-lock fa-login"></i>
                    <input type="password" placeholder="Password" class="input-login" required name="passw">
                </div>
                <div class="btn-login-float">
                    <button class="btn-login" type="submit">Login</button>
                </div>
            </form>
            <form action="create-account.php" method="post">
                <div class="btn-login-float">
                    <button class="btn-login" type="submit">Create Account</button>
                </div>
            </form>
        </div>
    </body>    
</html>