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
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        
        function fnReturnHome() {
            if(confirm('Are you sure? Any changes you made will not be saved.'))
                document.location.href = 'index.php';
        }
        
        function addNewGroupBox() {
            var box = document.createElement("div");
                box.setAttribute("class", "col-lg-12 col-md-12 col-sm-12 col-xs-12");
                box.setAttribute("id", "new");
            var icn = document.createElement("i");
                icn.setAttribute("class", "fa fa-lock fa-login");
            var inp = document.createElement("input");
                inp.setAttribute("type", "text");
                inp.setAttribute("class", "input-settings");
                inp.setAttribute("name", "newGroup[]");
                inp.setAttribute("style", "margin:4px");
            var btn = document.createElement("i");
                btn.setAttribute("class", "fa fa-minus fa-settings-remove-room");
                btn.setAttribute("onclick", "removeGroupBox(this.parentNode)");
            box.appendChild(btn);
            box.insertBefore(inp, btn);
            box.insertBefore(icn, inp);
            
            document.getElementById("groupList").appendChild(box);
        }
        
        function removeGroupBox(target=-1) {
            
            if (target == -1) return;
            var id = target.id;
            
            if (id == "new") {
                document.getElementById("groupList").removeChild(target);   
            } else {
                var inp = target.getElementsByTagName("input")[0];
                inp.setAttribute("name", "delGroups[]");
                inp.setAttribute("value", "" + target.id);
                target.style.display = "none";
            } 
        }
        
        function removeAccountBox(target=-1) {
            
            if (target == -1) return;
            var id = target.id;
            
            if (id == "new") {
                document.getElementById("accountList").removeChild(target);   
            } else {
                target.removeChild(target.getElementsByTagName("select")[0]);
                var inp = target.getElementsByTagName("input")[0];
                inp.setAttribute("name", "delAccounts[]");
                inp.setAttribute("value", "" + target.id);
                target.style.display = "none";
            }
        }
    </script>
    
    <body class="login-body">
        <div class="component-settings-form-container">
            <button class="btn-back" onclick="fnReturnHome()"><i class="fa fa-arrow-left" style="font-size: 10px;"></i> <i class="fa fa-home"></i></button>
            <h1 class="text-center h1-settings">Account Configuration</h1>
                <?php
                    $sql = "select * from Groups";
                    $result = mysqli_query($conn,$sql);
                    $groups = array();

                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                        $groups[] = $row;
                    }
            
                    if (isset($_SESSION['accountSetMsg'])) {
                        echo '<label id="houseErrorText" class="lbl-setup-house-visible col-lg-12 col-md-12 col-sm-12 col-xs-12">' . $_SESSION['accountSetMsg'] . '</label>';
                        unset($_SESSION['accountSetMsg']);
                    } else {
                        echo '<label id="houseErrorText" class="lbl-setup-house-hidden col-lg-12 col-md-12 col-sm-12 col-xs-12"></label>';
                    }
                ?>
                <form id="myForm" action="groupSettingsScript.php" method="post">
                    <div id="groupList" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="text-center p-user"><b>Custom Groups</b></p>

                        <?php
                            $sql2 = "select * from Groups where Groups_gID>2";
                            $result2 = mysqli_query($conn,$sql2);
                        
                            if (mysqli_num_rows($result2) == 0) {
                                echo '<p>There are no custom groups.</p>';
                            } else {

                                while ($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
                                    echo '<div id="' . $row2['Groups_gID'] . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <i class="fa fa-lock fa-login"></i>
                                            <input type="text" placeholder="Room Name" value="' . $row2['Groups_Name'] . '" class="input-settings" name="updateGroups[' . $row2['Groups_gID'] . '][]">
                                            <i class="fa fa-minus fa-settings-remove-room" onclick="removeGroupBox(this.parentNode)"></i>
                                          </div>';
                                }
                            }
                        ?>

                    </div>

                    <?php
                        $sql3 = "select * from User where not User_ID=" . $_SESSION['uID'];
                        $result3 = mysqli_query($conn,$sql3);
                    ?>

                    <div id="accountList col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="text-center p-user col-lg-12 col-md-12 col-sm-12 col-xs-12"><b>Other Accounts</b></p>
                        <?php

                            if (mysqli_num_rows($result3) == 0) {
                                echo '<p class="col-lg-12 col-md-12 col-sm-12 col-xs-12">There are no other accounts.</p>';
                            } else {

                                while ($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                                    echo '<div id="' . $row3['User_ID'] . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <i class="fa fa-user fa-login"></i>
                                            <input style="width:56%" type="text" value="' . $row3['Username'] . '" class="input-settings" name="updateAccounts[' . $row3['User_ID'] . '][]">
                                            <i class="fa fa-minus fa-settings-remove-room" onclick="removeAccountBox(this.parentNode)"></i>
                                            <select class="fa fa-lock fa-login user-dropdown-2" name="updateAccounts[' . $row3['User_ID'] . '][]">';
                                                foreach ($groups as $row) {

                                                    if ($row3['User_gID'] == $row['Groups_gID']) {
                                                        echo '<option selected value="' . $row['Groups_gID'] . '">' . $row['Groups_Name'] .'</option>';
                                                    } else {
                                                        echo '<option value="' . $row['Groups_gID'] . '">' . $row['Groups_Name'] .'</option>';
                                                    }
                                                }
                                    echo '</select><i class="fas fa-caret-down user-caret"></i></div>';
                                }
                            }
                        ?>
                    </div>
                <div id="buttonDiv" class="btn-login-float col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn-save-house">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>