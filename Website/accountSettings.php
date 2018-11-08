<?php
    require('config.php');

    if($_SESSION["guest"] == true) {
        $_SESSION['loginMsg'] = "Please login first.";
        header("Location: login.php");
        die();
    }
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Account Settings</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <script>
        function fnReturnHome() {
            if(confirm('Are you sure? Any changes you made will not be saved.'))
                document.location.href = 'index.php';
        }
    </script>

    <body class="login-body">
        <div class="create-account-form-container">
            <button class="btn-back" onclick="fnReturnHome()"><i class="fa fa-arrow-left" style="font-size: 10px;"></i> <i class="fa fa-home"></i></button>
            <form action="accountSettingsScript.php" method="post">
                <h1 class="text-center h1-main-header">Change Account Info</h1>
                <?php
                    if (!isset($_SESSION['accountMsg'])) {
                        echo '<label id="accountErrorText" class="lbl-create-account-hidden"></label>';
                    } else { 
                        echo '<label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['accountMsg'] . '</label>';
                        unset($_SESSION['accountMsg']);
                    }
                
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <i class="fa fa-user fa-login"></i>
                            <input id="username" type="text" value="' . $_SESSION['user'] . '" class="input-login" required name="uname">
                          </div>';
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i id="password_check" class="fa fa-lock fa-login"></i>
                    <input id="password" type="password" placeholder="New Password" class="input-login" name="passw1">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i id="confirm_password_check" class="fa fa-lock fa-login"></i>
                    <input id="confirm_password" type="password" placeholder="Confirm New Password" class="input-login" name="passw2">
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button id="" class="btn-create btn-create-enabled" type="submit" >Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>