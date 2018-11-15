<?php
require('config.php');

if($_SESSION["guest"] == true) {
    $_SESSION['loginMsg'] = "Please login first.";

    header("Location: login.php");
    die();
}
?>
<html>
    <body class="login-body">
        <div class="component-settings-form-container">
            <button class="btn-back" onclick="fnReturnHome()"><i class="fa fa-arrow-left" style="font-size: 10px;"></i> <i class="fa fa-home"></i></button>
            <h1 class="text-center h1-settings">WARNING</h1>
            <label id="componentErrorText" class="lbl-new-component-hidden"></label>
            <form class="component-settings-card row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-home fa-login"></i>
                    <p>
                        <?php
                            if (array_key_exists($_POST['source']) && array_keyexists($_POST['confMsg'])) {
                                echo $_POST['confMsg'];
                                
                                $url = $_POST['source'];
                                $data['confirmed'];
                                $options = array(
                                    'http' => array(
                                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                        'method'  => 'POST',
                                        'content' => http_build_query($data)
                                    )
                                );
                                $context  = stream_context_create($options);
                                $result = file_get_contents($url, false, $context);
                                
                                if ($result === FALSE) {
                                    if (isset($_SESSION['houseSetMsg'])) {
                                        $_SESSION['houseSetMsg'] .= "<br>Something went wrong.";
                                    } else {
                                        $_SESSION['houseSetMsg'] = "Something went wrong.";
                                    }
                                }
                            } else {
                                if (isset($_SESSION['houseSetMsg'])) {
                                    $_SESSION['houseSetMsg'] .= "<br>POST is not constructed correctly.";
                                } else {
                                    $_SESSION['houseSetMsg'] = "POST is not cnostructed correctly.";
                                }
                            }
                        ?>
                    </p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button type="button" class="btn-component-switch btn-setting-option btn-cancel" onclick="deleteComponent()">Delete</button>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button type="button" class="btn-component-switch btn-setting-option btn-save-appliance" onclick="saveComponent()">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>