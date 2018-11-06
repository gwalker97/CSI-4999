<?php
require('config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Setup House</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <script>
        $(function() { 
            $('#houseName').on('keydown', function () {
                if ($('#houseName').val().length > 0 && $('#roomName').val().length > 0) {
                    $('#btnFinishAccount').removeAttr('disabled');
                    $('#btnFinishAccount').removeClass('btn-create-disabled');
                    $('#btnFinishAccount').addClass('btn-create-enabled');
                    $('#houseErrorText').removeClass('lbl-setup-house-visible').addClass('lbl-setup-house-hidden').html('');
                } else {
                    $('#btnFinishAccount').attr('disabled','disabled');
                    $('#btnFinishAccount').removeClass('btn-create-enabled');
                    $('#btnFinishAccount').addClass('btn-create-disabled');
                    $('#houseErrorText').removeClass('lbl-setup-house-hidden').addClass('lbl-setup-house-visible').html('Please name your house and setup a room.');
                }
            });
        });

        $(function() { 
            $('#roomName').on('keydown', function () {
                if ($('#roomName').val().length > 0 && $('#houseName').val().length > 0) {
                    $('#btnFinishAccount').removeAttr('disabled');
                    $('#btnFinishAccount').removeClass('btn-create-disabled');
                    $('#btnFinishAccount').addClass('btn-create-enabled');
                    $('#houseErrorText').removeClass('lbl-setup-house-visible').addClass('lbl-setup-house-hidden').html('');
                } else {
                    $('#btnFinishAccount').attr('disabled','disabled');
                    $('#btnFinishAccount').removeClass('btn-create-enabled');
                    $('#btnFinishAccount').addClass('btn-create-disabled');
                    $('#houseErrorText').removeClass('lbl-setup-house-hidden').addClass('lbl-setup-house-visible').html('Please name your house and setup a room.');
                }
            });
        });
    </script>

    <body class="login-body">
        <div class="component-settings-form-container">
            <form action="setupHouseScript.php" method="post">
                <h1 class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center h1-main-header">Almost there!</h1>
                <?php
                    if (!isset($_SESSION['houseMsg'])) {
                        echo '<label id="accountErrorText" class="lbl-create-account-hidden"></label>';
                    } else { 
                        echo '<label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['houseMsg'] . '</label>';
                        unset($_SESSION['houseMsg']);
                    }
                ?>
                <label id="houseErrorText" class="lbl-setup-house-hidden"></label>
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-home fa-login"></i>
                    <input type="text" id="houseName" placeholder="House Name" class="input-login" name="hName" required>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-map-marker fa-login"></i>
                    <input type="text" id="roomName" placeholder="Room Name" class="input-login" name="rName" required>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" id="btnFinishAccount" class="btn-create btn-create-disabled" disabled>Finish</button>
                </div>
            </form>
        </div>
    </body>    
</html>