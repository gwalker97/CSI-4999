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
        <title>House Settings</title>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
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
        
        function fnReturnHome() {
            if(confirm('Are you sure? Any changes you made will not be saved.'))
                document.location.href = 'index.php';
        }
        
        function addNewRoomBox() {
            var box = document.createElement("div");
                box.setAttribute("class", "col-lg-12 col-md-12 col-sm-12 col-xs-12");
                box.setAttribute("style", "margin-left:-5px;");
                box.setAttribute("id", "new");
            var icn = document.createElement("i");
                icn.setAttribute("class", "fa fa-map-marker fa-login");
                icn.setAttribute("style", "margin-right:-1px;");
            var inp = document.createElement("input");
                inp.setAttribute("type", "text");
                inp.setAttribute("placeholder", "Room Name");
                inp.setAttribute("class", "input-settings");
                inp.setAttribute("name", "newRooms[]");
                inp.setAttribute("style", "width: 53%;");
            var btn = document.createElement("i");
                btn.setAttribute("class", "fa fa-minus fa-settings-remove-room");
                btn.setAttribute("style", "margin-left:-1px;");
                btn.setAttribute("onclick", "removeRoomBox(this.parentNode)");
            var prm = document.createElement("select");
                prm.setAttribute("class", "fa fa-lock fa-login user-dropdown");
                prm.setAttribute("style", "margin-left: -1px;")
            box.appendChild(prm);
            box.insertBefore(btn, prm);
            box.insertBefore(inp, btn);
            box.insertBefore(icn, inp);
            
            document.getElementById("myForm").insertBefore(box, document.getElementById("buttonDiv"));
        }
        
        function removeRoomBox(target=-1) {
            if (target == -1) return;
            var id = target.id;
            if (id == "new") {
                document.getElementById("myForm").removeChild(target);   
            } else {
                target.removeChild(target.getElementsByTagName("select")[0]);
                var inp = target.getElementsByTagName("input")[0];
                inp.setAttribute("name", "delRooms[]");
                inp.setAttribute("value", "" + target.id);
                target.style.display = "none";
            }
        }
    </script>
    
    <body class="login-body">
        <div class="component-settings-form-container">
            <button class="btn-back" onclick="fnReturnHome()"><i class="fa fa-arrow-left" style="font-size: 10px;"></i> <i class="fa fa-home"></i></button>
            <h1 class="text-center h1-settings">House Configuration</h1>
                <?php
                    if (isset($_SESSION['home'])) {
                        $hID = $_SESSION['home'];
                        $sql = "select * from Room where House_ID='$hID'";
                        $result = mysqli_query($conn,$sql);
                        $count = mysqli_num_rows($result);

                        $sql2 = "select * from House where House_ID='$hID'";
                        $result2 = mysqli_query($conn,$sql2);
                        $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);

                        if (isset($_SESSION['houseSetMsg'])) {
                            echo '<label id="houseErrorText" class="lbl-setup-house-visible">' . $_SESSION['houseSetMsg'] . '</label>';
                            unset($_SESSION['houseSetMsg']);
                        } else {
                            echo '<label id="houseErrorText" class="lbl-setup-house-hidden"></label>';
                        }
                ?>
                <form id="myForm" action="house-settingsScript.php" method="post">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-home fa-login"></i>
                <?php
                        echo '<input style="width:72%" type="text" placeholder="House Name" value="' . $row2['House_Name'] . '" class="input-login" name="houseName">
                              <i class="fa fa-plus fa-settings-add-room" onclick="addNewRoomBox()"></i>';
                ?>
                </div>
                <?php
                        $sql3 = "select * from Groups";
                        $result3 = mysqli_query($conn,$sql3);
                        $groups = array();
                        
                        while ($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                            $groups[] = $row3;
                        }
                        
                        if ($count == 0) {
                            echo '<div class="col-md-12">
                                    <i class="fa fa-map-marker fa-login"></i>
                                    <input type="text" placeholder="Room Name" class="input-settings" name="newRooms[]">
                                    <select style="background-color:black" class="fa fa-lock fa-login"></select></div>';
                        } else {
                            
                            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                                echo '<div id="' . $row['Room_ID'] . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <i class="fa fa-map-marker fa-login"></i>
                                        <input style="width:53%" type="text" placeholder="Room Name" value="' . $row['Room_Name'] . '" class="input-settings" name="updateRooms[' . $row['Room_ID'] . '][]">
                                        <i class="fa fa-minus fa-settings-remove-room" onclick="removeRoomBox(this.parentNode)"></i>
                                            <select class="fa fa-lock fa-login user-dropdown" name="updateRooms[' . $row['Room_ID'] . '][]">';
                                                foreach ($groups as $row3) {
                                                    
                                                    if ($row['Room_gID'] == $row3['Groups_gID']) {
                                                        echo '<option selected value="' . $row3['Groups_gID'] . '">' . $row3['Groups_Name'] .'</option>';
                                                    } else {
                                                        echo '<option value="' . $row3['Groups_gID'] . '">' . $row3['Groups_Name'] .'</option>';
                                                    }
                                                }
                                echo '</select><i class="fas fa-caret-down user-caret"></i>
                                      </div>';
                                
                            }
                        }
                    } else { 
                        echo '<label id="houseErrorText" class="lbl-setup-house-visible">You are not assigned to a house.</label>';
                    }
                ?>
                <div id="buttonDiv" class="btn-login-float col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn-save-house">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>