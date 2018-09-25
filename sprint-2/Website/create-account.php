<!DOCTYPE html>
<html>
    <head>
        <title>HARP Login</title>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        window.onload = function(){
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
        };
        
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
    </script>
    
    <body class="login-body">
        <div class="create-account-form-container">
            <form action="createAccount.php" method="get">
                <h1 class="text-center h1-main-header">New Account</h1>
                <?php
                    if (!isset($_SESSION['createMsg'])) {
                        echo '<label id="accountErrorText" class="lbl-create-account-hidden"></label>';
                    } else { 
                        echo '<label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['createMsg'] . '</label>';
                    }
                ?>
                <div class="col-md-12">
                    <i class="fa fa-user fa-login"></i>
                    <input type="text" placeholder="Username" class="input-login" required name="uname">
                </div>
                <div class="col-md-12">
                    <i class="fa fa-lock fa-login"></i>
                    <input type="password" placeholder="Password" class="input-login" required name="passw1">
                </div>
                <div class="col-md-12">
                    <i class="fa fa-lock fa-login"></i>
                    <input type="password" placeholder="Confirm Password" class="input-login" required name="passw2">
                </div>
                <div class="btn-login-float">
                    <button class="btn-login" type="submit">Create</button>
                </div>
            </form>
        </div>
    </body>    
</html>