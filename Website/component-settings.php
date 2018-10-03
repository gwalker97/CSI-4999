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
        <title>HARP Component</title>
	<!-- AJAX & jQuery CDN, must go before Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>        
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <script>
        window.onload = function(){
            var componentBehavior = getParameterByName("componentBehavior");
            
            if (componentBehavior == "componentExists")
            {
                fnSetComponentBehavior("There's already a component with this name.")
            }

		//Use Database to populate
		var urlArray = window.location.search.split('=');
		var compId = urlArray[1].substring(1);

		$.post(
			"readComponent.php",
			{ id: compId },
			function(response) {
				document.getElementById('AppName').value = response.name;
				document.getElementById('AppDesc').value = response.description;

				/*
				var ddl = document.getElementByID('RoomList');
				var opts = ddl.options.length;
				for (var i=0; i<opts; i++){
					if (ddl.options[i].value == response.name){
						ddl.options[i].selected = true;
						break;
					}
				}
				*/
			}, 'json'
		);


        };
        
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

	function fnReturnToLogin() {
            window.location.href='/login.php';
        }
    </script>
    
    <body class="login-body">
        <div class="component-settings-form-container">
	                <?php
                        $hID = $_SESSION['home'];
                        $sql = "select * from House where House_ID='$hID'";
		                $result = mysqli_query($conn,$sql);
                        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            
                        $hName = $row['House_Name'];
            
                        $count = mysqli_num_rows($result);
                    	?>
            <h1 class="text-center h1-settings">Component Settings</h1>
            <label id="componentErrorText" class="lbl-new-component-hidden"></label>
            <form class="component-settings-card row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-home fa-login"></i>
                    <input type="text" id="AppName" placeholder="Appliance Name" class="input-login">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <i class="fa fa-wrench fa-login"></i>
                    <input type="text" id="AppDesc" placeholder="Appliance Description" class="input-login">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <select id="roomList" class="select-rooms">
                                <?php
                                
				
				
				$compNum = substr(htmlspecialchars($_GET['component-id']), 1);
				/*
				$roomNum = mysqli_fetch_array(mysqli_query($conn,"SELECT Addon_Room_ID from Addon where Addon_ID='$compNum'"),MYSQLI_ASSOC);
				

print "[" . $compNum . "]";
print "[" . $roomNum . "]";
				$roomName = mysqli_query($conn,"SELECT Room_Name from Room where Room_ID=$roomNum");
*/

$sql3 = "SELECT A.Addon_Room_ID, Room.Room_Name 
                            FROM Room
                            INNER JOIN 
                            (select * from Addon where Addon_ID='$compNum') as A
                            ON Room.Room_ID=A.Addon_Room_ID";
                $result3 = mysqli_query($conn,$sql3);
		$row4 = mysqli_fetch_array($result3,MYSQLI_ASSOC);
		echo '<option selected="true" value="'.$row4['Addon_Room_ID'].'">'.$row4['Room_Name'].'</option>';

				$sql2 = "select * from Room where House_ID='$hID'";
                                $result2 = mysqli_query($conn,$sql2);
                                
                                while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
					if ($row2['Room_ID'] != $row4['Addon_Room_ID']){                                    
				$rID = $row2['Room_ID'];
                                    $rN = $row2['Room_Name'];
                                    echo '<option value="' . $rID . '">' . $rN . '</option>';
                                }}
                            ?>
                            </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button class="btn-component-switch btn-setting-option btn-cancel">Delete</button>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button class="btn-component-switch btn-setting-option btn-save-appliance">Save</button>
                </div>
            </form>
        </div>
    </body>    
</html>