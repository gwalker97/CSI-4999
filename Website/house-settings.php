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
        <title>HARP Config</title>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        /*window.onload = function(){
            var houseBehavior = getParameterByName("houseBehavior");
            
            if (houseBehavior == "roomExists")
            {
                fnSetHouseBehavior("There's already a room with this name.")
            }
        };*/
        
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        
        function fnSetHouseBehavior(arg) {
            var errorText = document.getElementById("houseErrorText");
            if (errorText.classList.contains('lbl-setup-house-hidden'))
            {
                errorText.innerHTML = arg;
                errorText.classList.remove('lbl-setup-house-hidden');
                errorText.classList.add('lbl-setup-house-visible');
            }
        }
    </script>
    
    <body class="login-body">
        <div class="component-settings-form-container">
            <form>
                <h1 class="col-md-12 text-center h1-settings">House Configuration</h1>
                <label id="houseErrorText" class="lbl-setup-house-hidden"></label>
                <div class="col-md-12">
                    <i class="fa fa-home fa-login"></i>
                    <input type="text" placeholder="House Name" class="input-login">
                </div>
                <?php
                    $hID = $_SESSION['home'];
                    $sql = "select * from Rooms where Username='$hID'";
                    $result = mysqli_query($conn,$sql);
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                    $count = mysqli_num_rows($result);
                
                    if ($count == 0) {
                        echo '<div class="col-md-12">
                                <i class="fa fa-map-marker fa-login"></i>
                                <input type="text" placeholder="Room Name" class="input-settings">
                                <i class="fa fa-plus fa-settings-add-room"></i>
                                <i class="fa fa-minus fa-settings-remove-room"></i>
                              </div>';
                    } else {
                        
                    }
                ?>
                <div class="col-md-12">
                    <i class="fa fa-map-marker fa-login"></i>
                    <input type="text" placeholder="Room Name" class="input-settings">
                    <i class="fa fa-plus fa-settings-add-room"></i>
                    <i class="fa fa-minus fa-settings-remove-room"></i>
                </div>
                <div class="col-md-12">
                    <i class="fa fa-map-marker fa-login"></i>
                    <input type="text" placeholder="Room Name" class="input-settings">
                    <i class="fa fa-plus fa-settings-add-room"></i>
                    <i class="fa fa-minus fa-settings-remove-room"></i>
                </div>
                <div class="col-md-12">
                    <i class="fa fa-map-marker fa-login"></i>
                    <input type="text" placeholder="Room Name" class="input-settings">
                    <i class="fa fa-plus fa-settings-add-room"></i>
                    <i class="fa fa-minus fa-settings-remove-room"></i>
                </div>
                <div class="btn-login-float">
                    <button formaction="submit" class="btn-save-house">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>