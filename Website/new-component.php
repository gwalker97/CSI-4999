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
        <title>HARP Login</title>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        /*
        window.onload = function(){
            var componentBehavior = getParameterByName("componentBehavior");
            
            if (componentBehavior == "componentExists")
            {
                fnSetComponentBehavior("There's already a component with this name.")
            }
        };
        */
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        
        function fnSetComponentBehavior(arg) {
            var errorText = document.getElementById("componentErrorText");
            if (errorText.classList.contains('lbl-new-component-hidden'))
            {
                errorText.innerHTML = arg;
                errorText.classList.remove('lbl-new-component-hidden');
                errorText.classList.add('lbl-new-component-visible');
            }
        }
        
        function fnReturnHome() {
            if(confirm('Are you sure? Any changes you made will not be saved.'))
                document.location.href = 'index.php';
        }
    </script>
    
    <body class="login-body">
        <div class="component-settings-form-container">
            <button class="btn-back" onclick="fnReturnHome()"><i class="fa fa-arrow-left" style="font-size: 10px;"></i> <i class="fa fa-home"></i></button>
            <h1 class="text-center h1-settings">New Appliance</h1>
            <label id="componentErrorText" class="lbl-new-component-hidden"></label>
            <?php 
                if (isset($_SESSION['houseSetMsg'])) {
                    echo '<label id="componentErrorText" class="lbl-new-component-visible">' . $_SESSION['newCompMsg'] . '</label>';
                    unset($_SESSION['newCompMsg']);
                } else {
                    echo '<label id="componentErrorText" class="lbl-new-component-hidden"></label>';
                }
            ?>
            <form class="component-settings-card row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-home fa-login"></i>
                    <input type="text" placeholder="Appliance Name" class="input-login">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-wrench fa-login"></i>
                    <input type="text" placeholder="Appliance Description" class="input-login">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <select class="select-rooms">
                        <?php
                            $sql = "select * from Room where House_ID=" . $_SESSION['home'];
                            $result = mysqli_query($conn,$sql);

                            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                                $rID = $row['Room_ID'];
                                $rN = $row['Room_Name'];
                                echo '<option value="' . $rID . '">' . $rN . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button class="btn-component-save-cancel btn-setting-option btn-save-appliance">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>