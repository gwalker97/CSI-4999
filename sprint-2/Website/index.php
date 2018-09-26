<?php
    require('config.php');
    
    if($_SESSION["guest"] == true) {
        header("Location: login.php");
        die();
    } else { 
?>

        <!DOCTYPE html>
        <html>
            <head>
                <title>HARP</title>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
                <link href="CSS/main.css" type="text/css" rel="stylesheet">
                <meta name="viewport" content="width=device-width, initial-scale=1">
            </head>

            <script>
                function fnSwitchClick(clicked_id) {
                    var change = document.getElementById(clicked_id);
                    if (change.classList.contains('fa-unlock') || change.classList.contains('fa-lock'))
                    {
                        if (change.classList.contains('fa-lock'))
                        {
                            change.classList.remove('fa-lock');
                            change.classList.add('fa-unlock');
                            change.classList.remove('btn-off');
                            change.classList.add('btn-on');
                        }
                        else
                        {
                            change.classList.remove('fa-unlock');
                            change.classList.add('fa-lock');   
                            change.classList.remove('btn-on');
                            change.classList.add('btn-off');
                        }
                    }
                    else
                    {
                        if (change.innerHTML == "On")
                        {
                            change.innerHTML = "Off";
                        }
                        else {
                            change.innerHTML = "On";
                        }

                        if (change.classList.contains('btn-on'))
                        {
                            change.classList.remove('btn-on');
                            change.classList.add('btn-off');
                        }
                        else
                        {
                            change.classList.remove('btn-off');
                            change.classList.add('btn-on');   
                        }
                    }
                }

                function fnComponentSettingsRedirect(clicked_id) {
                    window.location.href='/component-settings.html?component-id=' + clicked_id
                }

                function fnReturnToLogin() {
                    window.location.href='/login.html';
                }
                
                function phpLogout() {
                    if(confirm('Are you sure you want to log out? Logging out will automatically turn off all appliances.'))
                        document.location.href = 'logout.php';
                }
            </script>

            <body class="login-body">
                <div class="main-page-container">
                    <?php
                        $hID = $_SESSION['home'];
                        $sql = "select * from House where House_ID='$hID'";
		                $result = mysqli_query($conn,$sql);
                        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            
                        $hName = $row['House_Name'];
            
                        $count = mysqli_num_rows($result);
                    ?>
                    <div>
                        <?php
                            if (!isset($_SESSION['indexMsg'])) {
                                echo '<center><label id="accountErrorText" class="lbl-create-account-hidden"></label></center>';
                            } else { 
                                echo '<center><label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['indexMsg'] . '</label></center>';
                                unset($_SESSION['indexMsg']);
                            }
                        ?>
                        <h1 class="text-center h1-main-page"><?php echo $hName; ?></h1>
                        <button class="fa fa-sign-out-alt btn-sign-out" onclick="phpLogout()"></button>
                        <button class="fa fa-cog btn-sign-out btn-cog" onclick="window.location.href='/house-settings.html?house-id=1'"></button>
                    </div>
                    <div class="div-devices">
                        <div style="display: inline-block">
                            <h4 class="h4-devices">New Appliance</h4>
                            <button class="btn-new-component" onclick="window.location.href='/new-component.html?house-id=1'">+</button>
                        </div>
                        <div style="display: inline-block">
                            <select class="selects">
                                <option value="volvo">All Devices</option>
                                <option value="saab">Lights</option>
                                <option value="mercedes">Doors</option>
                                <option value="audi">Fans</option>
                            </select>
                            <select id="roomList" class="selects">
                                <?php
                                $sql2 = "select * from Room where House_ID='$hID'";
                                $result2 = mysqli_query($conn,$sql2);
                                
                                echo '<option value="0">All Rooms</option>';
                                while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
                                    $rID = $row2['Room_ID'];
                                    $rN = $row2['Room_Name'];
                                    echo '<option value="' . $rID . '">' . $rN . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <?php
                        $sql3 = "SELECT Addon.Addon_Name, Addon.Addon_Description, Addon.Addon_ID, A.Room_Name, Addon.Addon_State 
                                    FROM Addon 
                                    INNER JOIN 
                                    (select * from Room where House_ID=1) as A
                                    ON A.Room_ID=Addon.Addon_Room_ID;";
                        $result3 = mysqli_query($conn,$sql3);
                        $i = -1;
                        while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                            $aN = $row3['Addon_Name'];
                            $aD = $row3['Addon_Description'];
                            $aID = $row3['Addon_ID'];
                            $rN = $row3['Room_Name'];
                            $aS = $row3['Addon_State'];
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="component-card">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <p class="p-component-main">' . $aN . '</p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <button class="btn-component-switch btn-off" id="b' . $aID . '" onclick="fnSwitchClick(this.id)">Off</button>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <p class="p-component-label"><b>Room:</b> ' . $rN . '</p>
                                            <p class="p-component-label"><b>Description:</b> ' . $aD . '</p>

                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <button class="btn-component-switch fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                        </div>
                                    </div>
                                  </div>';
                        }
                    ?>
                </div>
            </body>    
        </html>
<?php    } 
?>