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
        <title>HARP</title>
	<!-- AJAX & jQuery CDN, must go before Bootstrap -->
	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
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

			//Sends the button ID and (minus first character) and 0 to PHP
			$.post("saveDatabase.php",
			    {
				id: change.id.substring(1),
				state: '0',
			    });
                }
                else {
                    change.innerHTML = "On";

			//Sends the button ID (minus first character) and 1 to PHP
			$.post("saveDatabase.php",
			    {
				id: change.id.substring(1),
				state: '1',
			    });
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
        
        function fnSwitchClickSlider(clicked_id) {
            var change = document.getElementById(clicked_id);
            var sliderID = "s" + change.id.substring(1);
            var sliderChange = document.getElementById(sliderID);

            if (change.innerHTML == "On")
            {
                change.innerHTML = "Off";

			//Sends the button ID and (minus first character) and 0 to PHP
                $.post("saveDatabase.php",
			    {
				    id: change.id.substring(1),
				    state: '0',
			    });
            }
            else {
                change.innerHTML = "On";

                //Sends the button ID (minus first character) and 1 to PHP
                $.post("saveDatabase.php",
			    {
				    id: change.id.substring(1),
				    state: '1',
			    });
            }
            if (change.classList.contains('btn-on'))
            {
                change.classList.remove('btn-on');
                change.classList.add('btn-off');
                sliderChange.value = "0.00";
            }
            else
            {
                change.classList.remove('btn-off');
                change.classList.add('btn-on');   
                sliderChange.value = "1.00";
            }
        }
        
        function fnComponentSettingsRedirect(clicked_id) {
            window.location.href='/component-settings.php?component-id=' + clicked_id
        }

        function fnReturnToLogin() {
            window.location.href='/login.html';
        }

        function phpLogout() {
            if(confirm('Are you sure you want to log out? Logging out will automatically turn off all appliances.'))
                document.location.href = 'logoutScript.php';
        }
        
        $(function() {
            $('#roomList').change(function(){
                var room = $(this).val();
                room = room.replace(/\s/g,'');
                if($(this).val() == "All Rooms") {
                    $('.rooms').show();
                }
                else {
                    $('.rooms').hide();
                    $('.rooms').each(function(i, obj) {
                        if(this.id == room) {
                            $('.' + this.id).show();
                        }
                    });
                }
            });
        });

	   //Function to check button states and update	
        function checkButtons() {
            //Find all buttons, store
            var className = document.getElementsByClassName('btn-component-switch');
            var classnameCount = className.length;
            var IdStore = new Array();
            for(var j = 0; j < classnameCount; j++){
                IdStore.push(className[j].id);
            }
            //For buttons with prefix "b", store just number
            var idArr = new Array();			
            var arrayLength = IdStore.length;
            for (var i = 0; i < arrayLength; i++) {			    
                if (IdStore[i].substring(0,1) == "b"){
                    idArr.push(IdStore[i]);
                }
            }

            //Use idArr to check database
            var arrayLength2 = idArr.length;
            for (var i = 0; i < arrayLength2; i++){					
                //"let" is better than "for" for AJAX						
                let tempButton = idArr[i];	
                $.post(
                    "readButton.php",
                    { id: (tempButton.substring(1)) },
                    function(response) {
                        if (Number(response.state) > Number(0)){							
                            document.getElementById(tempButton).innerHTML = "On";
                            document.getElementById(tempButton).classList.remove('btn-off');
                            document.getElementById(tempButton).classList.add('btn-on');
                        }
                        else {
                            document.getElementById(tempButton).innerHTML = "Off";
                            document.getElementById(tempButton).classList.remove('btn-on');
                            document.getElementById(tempButton).classList.add('btn-off');
                        }

                    }, 'json'
                );
            }
        }

        //Function to check slider states and update	
        function checkSliders() {
            //Find all sliders, store
            var className = document.getElementsByClassName('vertical-range');
            var classnameCount = className.length;
            var IdStore = new Array();
            for(var j = 0; j < classnameCount; j++){
                IdStore.push(className[j].id);
            }
            //For sliders with prefix "s", store just number
            var idArr = new Array();			
            var arrayLength = IdStore.length;
            for (var i = 0; i < arrayLength; i++) {			    
                if (IdStore[i].substring(0,1) == "s"){
                    idArr.push(IdStore[i]);
                }
            }

            //Use idArr to check database
            var arrayLength2 = idArr.length;
            for (var i = 0; i < arrayLength2; i++){					
                //"let" is better than "for" for AJAX						
                let tempButton = idArr[i];	    
                //If slider is not active                
                if (SliderActive == 0){
				    $.post(
		                "readButton.php",
		                { id: (tempButton.substring(1)) },
		                function(response) {			
		                        document.getElementById(tempButton).value = Number(response.state);

		                }, 'json'
		            );
                }
            }
        }
        
        //function to check Temp and update
        function checkTemp() {
            var idNameF = document.getElementById('displayTempF');
            var idNameC = document.getElementById('displayTempC');
            var IdStore = new Array();
            IdStore.push(idNameF.id);
            IdStore.push(idNameC.id)
            
            var idArr = new Array();			
            for (var i = 0; i < IdStore.length; i++) {	
                idArr.push(IdStore[i]);
            }
            
            $.post(
                "readTemp.php",
                function(response) {			
                    document.getElementById('displayTempF').innerHTML = Number(response.F) + "°";
                    document.getElementById('displayTempC').innerHTML = Number(response.C) + "°";
                }, 'json'
            );
        }

        //Execute functions every 2 seconds
        window.setInterval(function(){
            checkButtons();
            checkSliders();
            checkTemp();
        }, 2000);

        //When a slider moves
        $(document).ready(function(){
            $("[type=range]").change(function(){
                $.post("saveDatabase.php", {
                    id: $(this).attr("id").substring(1),
                    state: $(this).val(),
                });
                var tempButton = 'b' + $(this).attr("id").substring(1);
                if ($(this).val() > 0) {
                    document.getElementById(tempButton).innerHTML = "On";
                    document.getElementById(tempButton).classList.remove('btn-off');
                    document.getElementById(tempButton).classList.add('btn-on');
                }
                else {
                    document.getElementById(tempButton).innerHTML = "Off";
                    document.getElementById(tempButton).classList.remove('btn-on');
                    document.getElementById(tempButton).classList.add('btn-off');
                }
            });
        });
        
        //Sets variable if mouse is interacting with any slider
        var SliderActive = 0;
        $(document).ready(function(){
            $( "[type=range]" )
              .mouseenter(function() {
                SliderActive = 1;    
              })
              .mouseleave(function() {
                    SliderActive = 0;
              });
        });

        function fnTempChange(arg) {
            var btn = arg;
            var btnC = document.getElementById('btnTempC');
            var btnF =document.getElementById('btnTempF');
            var displayC = document.getElementById('displayTempC');
            var displayF = document.getElementById('displayTempF');
            
            if (btn == "btnTempC") {
                btnC.classList.remove('btn-temp-not-selected');
                btnC.classList.add('btn-temp-selected');
                btnF.classList.remove('btn-temp-selected');
                btnF.classList.add('btn-temp-not-selected');
                
                displayF.classList.remove('temp-display');
                displayF.classList.add('temp-display-off');
                displayC.classList.remove('temp-display-off');
                displayC.classList.add('temp-display');
            }
            else {
                btnC.classList.add('btn-temp-not-selected');
                btnC.classList.remove('btn-temp-selected');
                btnF.classList.add('btn-temp-selected');
                btnF.classList.remove('btn-temp-not-selected');
                
                displayF.classList.add('temp-display');
                displayF.classList.remove('temp-display-off');
                displayC.classList.add('temp-display-off');
                displayC.classList.remove('temp-display');
            }
        }
    </script>

            <body class="login-body">
                <div class="main-page-container">
                    <div class="temp-container">
                        <div class="temp-container-inside">
                            <span class="fas fa-thermometer-half"></span>
                            <?php 
                                $hID = $_SESSION['home'];
                                $sql = "select C, F from Temp where House_ID='$hID'";
                                $result = mysqli_query($conn,$sql);
                                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                            
                                $F = $row["F"];
                                $C = $row["C"];
                                
                                echo '<span id="displayTempF" class="temp-display">' . $F . '°</span>';
                                echo '<span id="displayTempC" class="temp-display-off">' . $C . '°</span>';
                            ?>

                            <button id="btnTempF" class="btn-temp-left btn-temp-selected" onclick="fnTempChange(this.id)">F</button>
                            <button id="btnTempC" class="btn-temp-right btn-temp-not-selected" onclick="fnTempChange(this.id)">C</button>
                        </div>
                    </div>
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
                        <h1 class="text-left h1-main-page"><?php echo $hName; ?></h1>
                        <div>
                            
                        </div>
<!--                        settings went here-->
                    </div>
                    <div class="div-devices">
                        <div style="display: inline-block">
                            <button class="btn-new-component" onclick="window.location.href='/new-component.html'">New<i class="fa fa-plus"></i></button>
                        </div>
                        <div style="display: inline-block">
                            <select id="roomList" class="selects">
                                <?php
                                $sql2 = "select * from Room where House_ID='$hID'";
                                $result2 = mysqli_query($conn,$sql2);
                                
                                echo '<option value="All Rooms">All Rooms</option>';
                                while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
                                    $rID = $row2['Room_ID'];
                                    $rN = $row2['Room_Name'];
                                    echo '<option value="' . $rN . '">' . $rN . '</option>';
                                }
                            ?>
                            </select>
                            <div style="width: 80px; margin-top: -6px; float: right;">
                                <button class="fa fa-sign-out-alt btn-sign-out" onclick="phpLogout()"></button>
                                <button class="fa fa-cog btn-sign-out btn-cog" onclick="window.location.href='house-settings.php'"></button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
<!--                    SCENES-->
                    <div class="scene-container">
                        <button class="scene-name scene-yellow">Morning
                        <button id="btnSceneSetting" class="btn-scene-settings" onclick="fnTempChange(this.id)"><span class="fa fa-info-circle fa-info-circle-scene"></span></button></button>
                    </div>
                    <div class="scene-container">
                        <div class="scene-container-inside">
                            <button class="scene-name scene-black">Night</button>
                            <button id="btnSceneSetting" class="btn-scene-settings" onclick="fnTempChange(this.id)"><span class="fa fa-info-circle fa-info-circle-scene"></span></button>
                        </div>
                    </div>
                    <div class="scene-container">
                        <div class="scene-container-inside">
                            <button class="scene-name scene-blue">Relax</button>
                            <button id="btnSceneSetting" class="btn-scene-settings" onclick="fnTempChange(this.id)"><span class="fa fa-info-circle fa-info-circle-scene"></span></button>
                        </div>
                    </div>
                    <div class="scene-container">
                        <div class="scene-container-inside">
                            <button class="scene-name">Study</button>
                            <button id="btnSceneSetting" class="btn-scene-settings" onclick="fnTempChange(this.id)"><span class="fa fa-info-circle fa-info-circle-scene"></span></button>
                        </div>
                    </div>
                    <div class="scene-container">
                        <div class="scene-container-inside">
                            <button class="scene-name scene-red">Movie</button>
                            <button id="btnSceneSetting" class="btn-scene-settings" onclick="fnTempChange(this.id)"><span class="fa fa-info-circle fa-info-circle-scene"></span></button>
                        </div>
                    </div>
<!--                    END SCENES-->
                    
                    
            <?php
                $sql3 = "SELECT Addon.Addon_Name, Addon.Addon_Description, Addon.Addon_ID, A.Room_Name, Addon.Addon_State, Addon.Addon_IsDim 
                            FROM Addon 
                            INNER JOIN 
                            (select * from Room where House_ID=" . $_SESSION['home'] . ") as A
                            ON A.Room_ID=Addon.Addon_Room_ID;";
                $result3 = mysqli_query($conn,$sql3);
                $i = -1;
                while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                    $aN = $row3['Addon_Name'];
                    $aD = $row3['Addon_Description'];
                    $aID = $row3['Addon_ID'];
                    $rN = $row3['Room_Name'];
                    $aS = $row3['Addon_State'];
                    $isD = $row3['Addon_IsDim'];
                    $strippedrN=preg_replace('/\s+/', '', $rN);
			//Sets variables based on Addon_State
			if ($aS > '0') {
	    			$buttonClass = "btn-component-switch btn-on";
				$buttonText = "On";
			} else{
	    			$buttonClass = "btn-component-switch btn-off";
				$buttonText = "Off";
			}
                    if ($isD == 0)
                    {
                    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN .'" id="' . $strippedrN . '">
                            <div class="component-card">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <p class="p-component-main"><i class="fa fa-lightbulb"></i>' . $aN . '</p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <button class="'. $buttonClass . '" id="b' . $aID . '" onclick="fnSwitchClick(this.id)">'. $buttonText . '</button>
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
                    else
                    {
                       echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN . '" id="' . $strippedrN . '">
                                <div class="component-card-slider">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div class="row">
                                            <p class="p-component-main"><img class="slider-icon" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHdpZHRoPSIxNnB4IiBoZWlnaHQ9IjE2cHgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CjxwYXRoIGZpbGw9IiNmZmQ1MDAiIGQ9Ik0xNiA2aC0zLjZjLTAuNy0xLjItMi0yLTMuNC0ycy0yLjggMC44LTMuNCAyaC01LjZ2NGg1LjZjMC43IDEuMiAyIDIgMy40IDJzMi44LTAuOCAzLjQtMmgzLjZ2LTR6TTEgOXYtMmg0LjFjMCAwLjMtMC4xIDAuNy0wLjEgMXMwLjEgMC43IDAuMSAxaC00LjF6TTkgMTFjLTEuNyAwLTMtMS4zLTMtM3MxLjMtMyAzLTMgMyAxLjMgMyAzYzAgMS43LTEuMyAzLTMgM3oiLz4KPC9zdmc+Cg==" />' . $aN . '</p>
                                        </div>

                                        <div class="row">
                                            <p class="p-component-label"><b>Room:</b> ' . $rN . '</p>
                                            <p class="p-component-label"><b>Description:</b> ' . $aD . '</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <input type="range" class="vertical-range" min="0" max="1" step=".05" value="'. $aS .'" id="s' . $aID .'" onchange="fnUpdateSlider(this.id)">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 special-slider-btns">
                                        <div class="row">
                                            <button class="'. $buttonClass . '" id="b' . $aID . '" onclick="fnSwitchClickSlider(this.id)">'. $buttonText . '</button>
                                        </div>
                                        <div class="row">
                                            <button class="btn-component-switch fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                        </div>
                                    </div>

                                </div>
                            </div>'; 
                    }
                }
            ?> 
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms" id="">
                            <div class="component-card">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <p class="p-component-main"><img class="fan-icon" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI5NS4xODIgMjk1LjE4MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjk1LjE4MiAyOTUuMTgyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPHBhdGggaWQ9IlhNTElEXzNfIiBkPSJNMjAwLjQ5OSwxMjQuNGM3Ljk3LTIuNzk3LDE2LjMxOC0xLjQ3NCwyNS4yNTgsMC40OWM1LjkwMSwxLjMsMTIuMDA2LDIuNjQ4LDE4Ljg1NCwyLjY0OCAgYzkuMTY1LDAsMTcuNzQ1LTIuNTEsMjYuMjMyLTcuNjY5YzEzLjA4MS03Ljk1NCwyMi4wMy0yMi4xODMsMjMuOTQtMzguMDYxYzIuMTc4LTE4LjE0My00LjYxOS0zNi4xNDItMTkuMTQ2LTUwLjY2NyAgYy0xNC40Mi0xNC40Mi0zMy42Ny0yMi4zNTctNTQuMjAzLTIyLjM1N2MtMjAuOTU1LDAtNDAuNzE1LDguMjItNTUuNjQyLDIzLjE0M2MtMTguNjU2LDE4LjY1Ny0zMC43OTMsNDguNDAzLTMxLjAwMyw3NS42ODQgIGMtNC40MzktMy4yMzEtOC4zNy03LjE4OS0xMC4zODktMTIuOTI5Yy0zLjEyNS04Ljg3Ny0xLjQ0OS0xNi40NjksMC40ODgtMjUuMjU5YzIuNjU4LTEyLjA0LDUuOTYtMjcuMDI1LTUuMDE5LTQ1LjA4NyAgQzExMC43NDMsOS4zMzQsOTMuNjU3LDAuMDExLDc1LjI3NCwwLjAxMWMtMTUuODU4LDAtMzEuNTMsNi45MzgtNDQuMTMxLDE5LjUzNEMxLjA3Niw0OS42MjIsMS40MzYsOTguODk4LDMxLjkzNCwxMjkuMzkgIGMxOC40MTEsMTguNDE2LDQ4LjY2NSwzMC44MTYsNzUuNjAxLDMxLjExOWMtMy4yMjEsNC4zOTUtNy4xNTksOC4yNjYtMTIuODUxLDEwLjI3MmMtNy45NTcsMi44MDgtMTYuMzA2LDEuNDkzLTI1LjI2MS0wLjQ4OSAgYy01Ljg5OS0xLjI5OS0xMi4wMDQtMi42NDktMTguODU2LTIuNjQ5Yy05LjE2MSwwLTE3Ljc0MiwyLjUwOC0yNi4yMjgsNy42NjdjLTEzLjA4MSw3Ljk1Ni0yMi4wMywyMi4xODQtMjMuOTQsMzguMDYzICBjLTIuMTc5LDE4LjE0Myw0LjYyMiwzNi4xNDMsMTkuMTQ1LDUwLjY2NmMxNC40MTksMTQuNDIsMzMuNjcxLDIyLjM1Niw1NC4yMDUsMjIuMzU2YzIwLjk1NCwwLDQwLjcxNi04LjIyLDU1LjY0Mi0yMy4xNDIgIGMxOC42NTYtMTguNjU3LDMwLjc5Mi00OC40MDIsMzEuMDA0LTc1LjY4M2M0LjQzNiwzLjIzLDguMzY3LDcuMTg5LDEwLjM4OSwxMi45MjdjMy4xMjQsOC44NzcsMS40NDksMTYuNDY5LTAuNDksMjUuMjYgIGMtMi42NTgsMTIuMDM5LTUuOTYsMjcuMDI1LDUuMDIsNDUuMDg3YzkuMTI2LDE1LjAwNCwyNi4yMTMsMjQuMzI3LDQ0LjU5NiwyNC4zMjdjMTUuODU5LDAsMzEuNTMxLTYuOTM5LDQ0LjEzMi0xOS41MzQgIGMzMC4wNjEtMzAuMDcxLDI5LjcwOC03OS4zNDctMC43OTItMTA5Ljg0NWMtMTguNDExLTE4LjQxOC00OC42NjYtMzAuODE3LTc1LjU5OS0zMS4xMTggIEMxOTAuODY5LDEzMC4yNzcsMTk0LjgwOSwxMjYuNDA2LDIwMC40OTksMTI0LjR6IE0xNjAuMTg3LDE2MC4xODdjLTYuNzI3LDYuNzI3LTE4LjQ2NSw2LjcyNy0yNS4xOTIsMCAgYy02Ljk0My02Ljk0OC02Ljk0My0xOC4yNDQsMC0yNS4xOTJjMy4zNjMtMy4zNjMsNy44MzYtNS4yMTUsMTIuNTk2LTUuMjE1YzQuNzYxLDAsOS4yMzIsMS44NTIsMTIuNTk3LDUuMjE1ICBDMTY3LjEyOSwxNDEuOTQzLDE2Ny4xMjksMTUzLjIzOSwxNjAuMTg3LDE2MC4xODd6IiBmaWxsPSIjMGE5MWM3Ii8+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />Fan #1</p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <button class="btn-component-switch btn-off" id="b" onclick="fnSwitchClick(this.id)">On</button>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <p class="p-component-label"><b>Room:</b> Living Room</p>
                                    <p class="p-component-label"><b>Description:</b> Above chair</p>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <button class="btn-component-switch fa fa-cog" id="c" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                </div>
                            </div>
                          </div>
        </div>
    </body>    
</html>

