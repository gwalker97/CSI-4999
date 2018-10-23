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
        <title>HARP - New Component</title>
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

	function saveComponent(){
			
		var urlArray = window.location.search.split('=');
		var compId = urlArray[1].substring(1);
			
			$.post("saveComponent.php",
			    {
				id: compId,
				name: document.getElementById('AppName').value,
				description: document.getElementById('AppDesc').value,
				roomid: document.getElementById("roomList").options[document.getElementById("roomList").selectedIndex].value,
			    });	
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
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h4 class="text-center h4-settings">Device Type:</h4>                
			<select id="deviceType" class="select-rooms">
			<option value="l">Light</option>
			<option value="s">Dimmable Light</option>
			<option value="f">Fan</option>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h4 class="text-center h4-settings">Room:</h4>                     
			<select id="roomList" class="select-rooms">
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
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h4 class="text-center h4-settings">Host Device:</h4> 
                    <select id="hostList" class="select-rooms">
                        <?php
                            $sql = "select * from Hosts";
                            $result = mysqli_query($conn,$sql);

                            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                                $hID = $row['Host_ID'];
                                $hN = $row['Host_Name'];
                                echo '<option value="' . $hID . '">' . $hN . '</option>';
                            }
                        ?>
                    </select>
                </div>
		<hr class="col-xs-12">		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-wrench fa-login"></i>
                    <input type="text" placeholder="Pin Number" class="input-login" disabled="true">
                </div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					
<img width="100%" src="Images/layouts/pi_org.png">

			<!-- Image Map Generated by http://www.image-map.net/ -->
			<img src="https://www.raspberrypi.org/documentation/usage/gpio/images/gpio-numbers-pi2.png" usemap="#image-map">

			<map name="image-map">
			    <area target="" alt="15" title="15" href="14" coords="432,87,19" shape="circle">
			    <area target="" alt="15" title="15" href="15" coords="490,84,22" shape="circle">
			    <area target="" alt="18" title="18" href="18" coords="551,85,24" shape="circle">
			    <area target="" alt="23" title="23" href="23" coords="670,85,23" shape="circle">
			    <area target="" alt="24" title="24" href="24" coords="728,85,28" shape="circle">
			    <area target="" alt="25" title="25" href="25" coords="847,85,25" shape="circle">
			    <area target="" alt="8" title="8" href="8" coords="911,84,25" shape="circle">
			    <area target="" alt="7" title="7" href="7" coords="970,84,24" shape="circle">
			    <area target="" alt="12" title="12" href="12" coords="1149,87,22" shape="circle">
			    <area target="" alt="16" title="16" href="16" coords="1270,84,28" shape="circle">
			    <area target="" alt="20" title="20" href="20" coords="1329,87,25" shape="circle">
			    <area target="" alt="21" title="21" href="21" coords="1387,84,22" shape="circle">
			    <area target="" alt="2" title="2" href="2" coords="313,159,23" shape="circle">
			    <area target="" alt="3" title="3" href="3" coords="373,155,24" shape="circle">
			    <area target="" alt="4" title="4" href="4" coords="432,158,24" shape="circle">
			    <area target="" alt="17" title="17" href="17" coords="553,156,26" shape="circle">
			    <area target="" alt="27" title="27" href="27" coords="612,159,23" shape="circle">
			    <area target="" alt="22" title="22" href="22" coords="672,155,27" shape="circle">
			    <area target="" alt="10" title="10" href="10" coords="792,156,24" shape="circle">
			    <area target="" alt="9" title="9" href="9" coords="849,157,28" shape="circle">
			    <area target="" alt="11" title="11" href="11" coords="910,156,28" shape="circle">
			    <area target="" alt="5" title="5" href="5" coords="1088,158,27" shape="circle">
			    <area target="" alt="6" title="6" href="6" coords="1148,158,24" shape="circle">
			    <area target="" alt="13" title="13" href="13" coords="1210,161,22" shape="circle">
			    <area target="" alt="19" title="19" href="19" coords="1267,159,22" shape="circle">
			    <area target="" alt="26" title="26" href="26" coords="1329,155,28" shape="circle">
			</map>
		</div>	
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn-component-save-cancel btn-setting-option btn-save-appliance">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>
