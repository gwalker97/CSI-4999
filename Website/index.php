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
            var changeID = change.id.substring(1);
            //Sends the button ID and (minus first character) and 0 to PHP
            $.post("saveDatabase.php",
                   {
                id: change.id.substring(1),
                state: '0',
            });
            if (change.innerHTML == "On")
            {
                change.innerHTML = "Off";
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

            var type = change.id.substring(0, 1)

            if (change.classList.contains('btn-on'))
            {
                if(type == "f") {
                    var elementID = 'f-image-' + changeID;
                    document.getElementById(elementID).classList.remove('spin');
                    document.getElementById(elementID).classList.add('anti-spin');
                }
                else if(type == "l") {
                    var elementID = 'l-image-' + changeID;
                    document.getElementById(elementID).classList.remove('fa-lightbulb-on');
                }
                else if(type == "s") {
                    var elementID = 's-image-' + changeID;
                    document.getElementById(elementID).classList.remove('slider-icon-flipped');
                }
                change.classList.remove('btn-on');
                change.classList.add('btn-off');
            }
            else
            {
                if(type == "f") {
                    var elementID = 'f-image-' + changeID;
                    document.getElementById(elementID).classList.add('spin');
                    document.getElementById(elementID).classList.remove('anti-spin');
                }
                else if(type == "l") {
                    var elementID = 'l-image-' + changeID;
                    document.getElementById(elementID).classList.add('fa-lightbulb-on');
                }
                else if(type == "s") {
                    var elementID = 's-image-' + changeID;
                    document.getElementById(elementID).classList.add('slider-icon-flipped');
                }
                change.classList.remove('btn-off');
                change.classList.add('btn-on');   
            }
        }

        function fnSwitchClickSlider(clicked_id) {
            var change = document.getElementById(clicked_id);
            var changeID = change.id.substring(1);
            var sliderID = "s" + changeID;
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

        $(function() {
            $('#colorSelect').change(function(){
                var color = $(this).val().toLowerCase();
                removeColorBrushClasses();
                if(color == "color") {

                }
                else if(color == "blue") {
                    $('#color-brush').addClass('scene-blue');
                }
                else if (color == "red") {
                    $('#color-brush').addClass('scene-red');
                }
                else if (color == "yellow") {
                    $('#color-brush').addClass('scene-yellow');                   
                }
                else if (color == "black") {
                    $('#color-brush').addClass('scene-black');                    
                }
                else if (color == "green") {
                    $('#color-brush').addClass('scene-green'); 
                }
                else if (color == "orange") {
                    $('#color-brush').addClass('scene-orange'); 
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
                if (IdStore[i].substring(0,1) == "l" || IdStore[i].substring(0,1) == "f" || IdStore[i].substring(0,1) == "b"){
                    idArr.push(IdStore[i]);
                }
            }

            //Use idArr to check database
            var arrayLength2 = idArr.length;
            for (var i = 0; i < arrayLength2; i++){					
                //"let" is better than "for" for AJAX						
                let tempButton = idArr[i];
                let type = tempButton.substring(0, 1);

                $.post(
                    "readButton.php",
                    { id: (tempButton.substring(1)) },
                    function(response) {
                        if (Number(response.state) > Number(0)){							
                            if(type == "f") {
                                var elementID = 'f-image-' + tempButton.substring(1);
                                document.getElementById(elementID).classList.add('spin');
                                document.getElementById(elementID).classList.remove('anti-spin');
                            }
                            else if(type == "l") {
                                var elementID = 'l-image-' + tempButton.substring(1);
                                document.getElementById(elementID).classList.add('fa-lightbulb-on');
                            }
                            else if(type == "b") {
                                var elementID = 's-image-' + tempButton.substring(1);
                                document.getElementById(elementID).classList.add('slider-icon-flipped');
                            }

                            document.getElementById(tempButton).innerHTML = "On";
                            document.getElementById(tempButton).classList.remove('btn-off');
                            document.getElementById(tempButton).classList.add('btn-on');
                        }
                        else {                            
                            if(type == "f") {
                                var elementID = 'f-image-' + tempButton.substring(1);
                                if(document.getElementById(elementID).classList.contains('spin')) {
                                    document.getElementById(elementID).classList.remove('spin');               document.getElementById(elementID).classList.add('anti-spin');
                                }
                            }
                            else if(type == "l") {
                                var elementID = 'l-image-' + tempButton.substring(1);
                                document.getElementById(elementID).classList.remove('fa-lightbulb-on');
                            }
                            else if(type == "b") {
                                var elementID = 's-image-' + tempButton.substring(1);
                                document.getElementById(elementID).classList.remove('slider-icon-flipped');
                            }

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
                var sliderSwitch = "b" + tempButton.substring(1);

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
            //Added semi-colon 11/13
            IdStore.push(idNameC.id);

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

        //Execute functions every 1 second(s)
        window.setInterval(function(){
            checkButtons();
            checkSliders();
            checkTemp();
        }, 1000);

        //When a slider moves
        $(document).ready(function(){
            $("[type=range]").change(function(){
                $.post("saveDatabase.php", {
                    id: $(this).attr("id").substring(1),
                    state: $(this).val(),
                });
                var tempButton = 'b' + $(this).attr("id").substring(1);
                var sliderIcon = 's-image' + $(this).attr("id").substring(1);
                if ($(this).val() > 0) {
                    document.getElementById(sliderIcon).classList.add('slider-icon-flipped');
                    document.getElementById(tempButton).innerHTML = "On";
                    document.getElementById(tempButton).classList.remove('btn-off');
                    document.getElementById(tempButton).classList.add('btn-on');
                }
                else {
                    document.getElementById(sliderIcon).classList.remove('slider-icon-flipped');
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

        function fnSetScene(arg) {
            fnLoad(true);
            //scene-x --> x
            var SceneID = arg.substring(6);
            $.post(
                "setScene.php",
                { id: (SceneID) },
                function(response) {			

                }, 'json'
            );
            fnLoad(false);
        }

        window.onload = function () { document.getElementById('loading').style.display = "none" }

        function fnLoad(arg) {
            if (arg) {
                document.getElementById('loading').style.display = "block";
            }
            else
            {
                setTimeout(function () {
                    document.getElementById('loading').style.display = "none";
                }, 850);
            }
        }

        function fnHideShowAutomation(arg) {
            var isAutomated = arg;            

            if (isAutomated == 1) {
                document.getElementById('automate-times').classList.remove('dont-display');
                document.getElementById('automate-times').classList.add('display');
            }
            else {
                document.getElementById('automate-times').classList.remove('display');
                document.getElementById('automate-times').classList.add('dont-display');
            }
        }

        function fnHideShowTempAutomation(arg) {
            var isAutomated = arg;

            if (isAutomated == 1) {
                document.getElementById('automate-temp').classList.remove('dont-display');
                document.getElementById('automate-temp').classList.add('display');
            }
            else {
                document.getElementById('automate-temp').classList.remove('display');
                document.getElementById('automate-temp').classList.add('dont-display');
            }
        }

        function fnSelectSceneAppliance(arg) {
            var appliance = document.getElementById(arg);

            if (appliance.classList.contains('appliance-selected')) {
                appliance.classList.remove('appliance-selected');
            }
            else {
                appliance.classList.add('appliance-selected');
            }
        }

        /*
        function fnSelectCompHost(arg) {
            var appliance = document.getElementById(arg);



            if (appliance.classList.contains('appliance-selected')) {
                appliance.classList.remove('appliance-selected');
                newCompSelectedApp = "0";
            }
            else {
                appliance.classList.add('appliance-selected');
                newCompSelectedApp = (appliance.id).split(/[-]+/).pop();
            }
        }
        */

        function fnClearSceneModal() {
            removeColorBrushClasses();

            document.getElementById('automate-times').classList.add('dont-display');

            document.getElementById('automate-error').classList.remove('display');
            document.getElementById('automate-error').classList.add('dont-display');

            $('.li-appliance').each(function(i, obj) {
                $('.li-appliance').removeClass('appliance-selected');
            });

            document.getElementById('lbl-scene-id').innerHTML = "";
            document.getElementById('btn-save-scene').innerHTML = "Create";

            document.getElementById('btn-delete-scene').classList.remove('display');
            document.getElementById('btn-delete-scene').classList.add('dont-display');

            document.getElementById('scene-form').reset();
        }

        function fnSaveScene(arg) {
            var sceneID = document.getElementById('lbl-scene-id').innerHTML;

            var sceneName = document.getElementById('scene-name').value;
            var sceneColor = document.getElementById('colorSelect').value;
            var sceneAutomated = document.querySelector('input[name="automate"]:checked').value;
            var sceneStart = document.getElementById('scene-start').value;
            var sceneEnd = document.getElementById('scene-end').value;
            var sceneTimeOkay = true;
            var sceneStartEndOkay = true;
            var sceneNameOkay = true;
            var sceneColorOkay = true;
            var addOnID = "";

            if (sceneAutomated == 1) {
                if (sceneStart == "" || sceneEnd == "") {
                    sceneTimeOkay = false;
                }
                if (sceneStart > sceneEnd || sceneStart == sceneEnd) {
                    sceneStartEndOkay = false;
                }
            }
            if (sceneName == "") {
                sceneNameOkay = false;
            }
            if (sceneColor == "color") {
                sceneColorOkay = false;
            }

            if (sceneNameOkay && sceneColorOkay && sceneTimeOkay && sceneStartEndOkay) {
                fnLoad(true);
                $('.appliance-selected').each(function(i, obj) {
                    var shortID = this.id.substring(10);
                    if (addOnID == "") {
                        addOnID = shortID;
                    }
                    else {
                        addOnID = addOnID + "," + shortID;
                    }
                });

                if (sceneID == "") {
                    $.post(
                        "newScene.php",
                        { sN: (sceneName), sC: (sceneColor), sA: (sceneAutomated), sS: (sceneStart), sE: (sceneEnd), aID: (addOnID),  },
                    );
                }
                else {
                    $.post(
                        "updateScene.php",
                        { sN: (sceneName), sC: (sceneColor), sA: (sceneAutomated), sS: (sceneStart), sE: (sceneEnd), aID: (addOnID), sID: (sceneID),  },
                    );
                }

                fnClearSceneModal();
                $('#myModal').modal('hide');
                $('#all-scenes').load(document.URL +  ' #all-scenes');
                fnLoad(false);
            }
            else {
                if (!sceneTimeOkay || !sceneStartEndOkay) {
                    if (!sceneTimeOkay) {
                        document.getElementById('automate-error-times').innerHTML = "You must enter a start and end time.";
                        document.getElementById('automate-error-times').classList.remove('dont-display');
                        document.getElementById('automate-error-times').classList.add('display');
                    }
                    else {
                        document.getElementById('automate-error-times').innerHTML = "Start time must be less than end time.";
                        document.getElementById('automate-error-times').classList.remove('dont-display');
                        document.getElementById('automate-error-times').classList.add('display');
                    }
                }
                if (!sceneNameOkay || !sceneColorOkay) {
                    document.getElementById('automate-error').innerHTML = "You must have a name, color, and at least one appliance.";
                    document.getElementById('automate-error').classList.remove('dont-display');
                    document.getElementById('automate-error').classList.add('display');
                }
            }
        }

        function fnDeleteScene(arg) {
            var sceneID = document.getElementById('lbl-scene-id').innerHTML;

            if(confirm('Are you sure you want to delete this scene?')) {
                fnLoad(true);

                $.post(
                    "deleteScene.php",
                    { sID: (sceneID),  },
                );

                fnClearSceneModal();

                $('#myModal').modal('hide');
                $('#all-scenes').load(document.URL +  ' #all-scenes');
                fnLoad(false);
            }
        }

        function fnAllOn() {
            fnLoad(true);
            $.post(
                "allOffOn.php",
                { OnOff: (1.00),  },
            );
            fnLoad(false);
        }

        function fnAllOff() {
            fnLoad(true);
            $.post(
                "allOffOn.php",
                { OnOff: (0.00),  },
            );
            fnLoad(false);
        }

        function fnSceneSettings(arg) {
            fnLoad(true);
            var sID = arg.substring(6);
            document.getElementById('lbl-scene-id').innerHTML = sID;
            document.getElementById('btn-save-scene').innerHTML = "Update";

            $.post(
                "sceneSettings.php",
                { sID: (sID),  },
                function(response) {	
                    document.getElementById('scene-name').value = response.Scene_Name;
                    document.getElementById('colorSelect').value = response.Scene_Color;
                    removeColorBrushClasses();
                    switch(response.Scene_Color) {
                        case 'blue':
                            $('#color-brush').addClass('scene-blue');
                            break;
                        case 'black':
                            $('#color-brush').addClass('scene-black');
                            break;
                        case 'red':
                            $('#color-brush').addClass('scene-red');
                            break;
                        case 'yellow': 
                            $('#color-brush').addClass('scene-yellow');
                            break;
                        case 'green':                                    
                            $('#color-brush').addClass('scene-green');
                            break;
                        case 'orange':
                            $('#color-brush').addClass('scene-orange');
                            break;
                        default:
                            break;
                                               }

                    if (response.Is_Automated == 1) {
                        $('#yesAutomated').prop('checked', true);  
                        document.getElementById('automate-times').classList.remove('dont-display');
                        document.getElementById('automate-times').classList.add('display');
                        document.getElementById('scene-start').value = response.Start_Time;
                        document.getElementById('scene-end').value = response.End_Time;
                    }
                    else {
                        $('#noAutomated').prop('checked', true);  
                        document.getElementById('automate-times').classList.remove('display');
                        document.getElementById('automate-times').classList.add('dont-display');
                    }
                }, 'json'
            );

            $.post(
                "sceneSettingsAddons.php",
                { sID: (sID),  },
                function(response) {	
                    removeSelectedAddons();
                    var addons = response.Addon_ID.split(',');
                    for(var i = 0; i < addons.length; i++) {
                        addons[i] = addons[i].replace(/^\s*/, "").replace(/\s*$/, "");
                        document.getElementById('scene-app-' + addons[i]).classList.add('appliance-selected');
                    }
                }, 'json'
            );

            document.getElementById('btn-delete-scene').classList.remove('dont-display');
            document.getElementById('btn-delete-scene').classList.add('display');

            $('#myModal').modal('show');
            fnLoad(false);
        }

        function removeColorBrushClasses() {
            $('#color-brush').removeClass('scene-blue').removeClass('scene-red').removeClass('scene-black').removeClass('scene-green').removeClass('scene-yellow').removeClass('scene-orange');
        }

        function removeSelectedAddons() {
            $('.li-appliance').removeClass('appliance-selected');
        }

        function fnNewAppPinSet(pin){

            document.getElementById("pin-number").value = pin;

            if (newCompSelectedApp == "0"){

                document.getElementById("new-comp-pin-text").innerHTML = "Please select host!";
            } else{
                $.post(
                    "checkPin.php",
                    { hID: (newCompSelectedApp), pin: (pin) },
                    function(response) {	

                        document.getElementById("new-comp-pin-text").innerHTML = response;

                    }, 'json'
                );
            }
        }

        var newCompSelectedApp = "0";

        $(function() {
            $('#newCompHostSelect').change(function(){
                newCompSelectedApp = document.getElementById('newCompHostSelect').value;

                document.getElementById("pin-number").value = "";
                document.getElementById("new-comp-pin-text").innerHTML = " ";
                document.getElementById('PiImg').classList.add('dont-display');
                document.getElementById('ESPImg').classList.add('dont-display');

                $.post(
                    "checkHostType.php",
                    { hID: (newCompSelectedApp) },
                    function(response) {	

                        if (response.model == "Pi") {
                            document.getElementById('PiImg').classList.remove('dont-display');
                            document.getElementById('PiImg').classList.add('display');
                        }
                        else if (response.model == "ESP") {
                            document.getElementById('ESPImg').classList.remove('dont-display');
                            document.getElementById('ESPImg').classList.add('display');
                        }

                    }, 'json'
                );          

            });
        });


        function fnClearApplianceModal() {

            document.getElementById('PiImg').classList.add('dont-display');
            document.getElementById('ESPImg').classList.add('dont-display');

            document.getElementById('appliance-name').innerHTML = "";
            document.getElementById('appliance-description').innerHTML = "";

            document.getElementById('btn-delete-scene').classList.remove('display');
            document.getElementById('btn-delete-scene').classList.add('dont-display');

            document.getElementById('comp-form').reset();
        }

        function fnSaveAppliance() {

            var appName = document.getElementById('appliance-name').value;
            var appType = document.getElementById('appliance-select').value;
            var appDescription = document.getElementById('appliance-description').value;
            var appRoom = document.getElementById('newCompRoomSelect').value;
            var appHost = document.getElementById('newCompHostSelect').value;
            var appPin = document.getElementById('pin-number').value;
            var appPinText = document.getElementById('new-comp-pin-text').innerHTML;

            var appNameOkay = true;
            var appTypeOkay = true;
            var appDescOkay = true;
            var appRoomOkay = true;
            var appHostOkay = true;
            var appPinOkay = true;

            if (appName == "") {
                appNameOkay = false;
            }
            if (appType == "") {
                appTypeOkay = false;
            }
            if (appDescription == "") {
                appDescOkay = false;
            }
            if (appRoom == "") {
                appRoomOkay = false;
            }
            if (appHost == "") {
                appHostOkay = false;
            }
            if (appPin == "" || appPinText == "Pin Taken!") {
                appPinOkay = false;
            }

            if (appNameOkay && appTypeOkay && appDescOkay && appRoomOkay && appHostOkay && appPinOkay) {

                $.post(
                    "newApp.php",
                    { aN: (appName), aD: (appDescription), aT: (appType), aR: (appRoom), aH: (appHost), aP: (appPin),  },
                );


                fnClearApplianceModal();
                $('#newCompModal').modal('hide');
                window.location.reload(true); 
            }
            else {
                if (!appNameOkay || !appDescOkay || !appRoomOkay || !appTypeOkay) {
                    document.getElementById('appliance-error-message').innerHTML = "Missing information!";
                    document.getElementById('appliance-error-message').classList.remove('dont-display');
                    document.getElementById('appliance-error-message').classList.add('display');
                }
                if (!appHostOkay || !appPinOkay) {
                    document.getElementById('appliance-error-message').innerHTML = "Host/Pin invalid!";
                    document.getElementById('appliance-error-message').classList.remove('dont-display');
                    document.getElementById('appliance-error-message').classList.add('display');
                }
            }
        }


    </script>

    <body class="login-body">

        <!--loading content-->
        <div id="loading">
            <div class="loading-box">
                <img id="loading-image" src="Images/loader.gif" alt="Loading..." />
            </div>
        </div>

        <!-- Modal content -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" onclick="fnClearSceneModal()">&times;</button>
                        <h4 class="modal-title">New Scene</h4>
                    </div>
                    <!-- Modal body-->
                    <div class="modal-body">
                        <!-- Modal form-->

                        <form id="scene-form" class="row">
                            <div class="automate-div">
                                <label id="automate-error" class="automate-error dont-display"></label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i class="fa fa-home fa-login"></i>
                                    <input type="text" id="scene-name" placeholder="Scene Name" class="input-login scene-name-input">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i id="color-brush" class="fas fa-paint-brush paint-brush fa-login"></i>
                                    <select id="colorSelect" class="selects color-select">
                                        <option value="color">Color</option>
                                        <option value="blue">Blue</option>
                                        <option value="green">Green</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="orange">Orange</option>
                                        <option value="red">Red</option>
                                        <option value="black">Black</option>
                                    </select>
                                    <i class="fas fa-caret-down color-caret"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="automate-div">
                                    <label class="lbl-automate">Would you like to automate this scene?</label>
                                    <!--<input id="automate-checkbox" type="checkbox" onclick="fnHideShowAutomation(this.id)" class="automate-checkbox">-->
                                    <label for="automated" style="margin: 0 5px;">Yes</label>
                                    <input type="radio" style="margin: 0 5px;" id="yesAutomated" name="automate" value="1" onclick="fnHideShowAutomation(this.value)"/>
                                    <label for="automated" style="margin: 0 5px;">No</label>
                                    <input type="radio" style="margin: 0 5px;" id="noAutomated" name="automate" value="0" onclick="fnHideShowAutomation(this.value)" checked />
                                </div>
                            </div>
                            <div id="automate-times" class="dont-display col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label id="automate-error-times" class="automate-error dont-display"></label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    Start Time
                                    <input placeholder="Start time" type="time" id="scene-start" class="input-login input-time">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    End Time
                                    <input placeholder="End time" type="time" id="scene-end" class="input-login input-time">
                                </div>
                            </div>
                            <div id="choose-appliance" class="appliance-list-selector col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="lbl-choose-appliances">Select the appliances you want in the scene.</label>
                                <ul id="appliance-list" class="ul-appliance-list">
                                    <?php
                                    $sql3 = "SELECT Addon.Addon_Name, Addon.Addon_ID 
                                                    FROM Addon 
                                                    INNER JOIN 
                                                    (select * from Room where House_ID=" . $_SESSION['home'] . ") as A
                                                    ON A.Room_ID=Addon.Addon_Room_ID;";
                                    $result3 = mysqli_query($conn,$sql3);
                                    $i = -1;
                                    while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                                        $aN = $row3['Addon_Name'];
                                        $aID = $row3['Addon_ID'];

                                        echo '<li id="scene-app-' . $aID . '" class="li-appliance" onclick="fnSelectSceneAppliance(this.id)">' . $aN . '</li>';

                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" id="btn-save-scene" class="btn-component-save-cancel btn-setting-option btn-save-appliance" onclick="fnSaveScene(this.id)">Create</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" id="btn-delete-scene" class="btn-component-save-cancel btn-setting-option btn-delete-appliance dont-display" onclick="fnDeleteScene(this.id)">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>

        <!-- New Component Modal content -->
        <div id="newCompModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" onclick="fnClearApplianceModal()">&times;</button>
                        <h4 class="modal-title">New Appliance</h4>
                    </div>
                    <!-- Modal body-->
                    <div class="modal-body">
                        <!-- Modal form-->

                        <form id="comp-form" class="row">
                            <label hidden id="lbl-scene-id"></label>
                            <div class="automate-div">
                                <label id="automate-error" class="automate-error dont-display"></label>
                            </div>
                            <div id="appliance-error" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label id="appliance-error-message" class="automate-error dont-display"></label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i class="fa fa-home fa-login"></i>
                                    <input type="text" id="appliance-name" placeholder="Appliance Name" class="input-login scene-name-input">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i id="color-brush" class="fas fa-home home fa-login"></i>
                                    <select id="appliance-select" class="selects color-select">
                                        <option value="L">Light</option>
                                        <option value="S">Dimmable Light</option>
                                        <option value="F">Fan</option>
                                    </select>
                                    <i class="fas fa-caret-down color-caret"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i class="fa fa-home fa-login"></i>
                                    <input type="text" id="appliance-description" placeholder="Appliance Description" class="input-login scene-name-input">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <i class="fas fa-home home fa-login"></i>
                                    <select id="newCompRoomSelect" class="selects color-select">
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
                                    <i class="fas fa-caret-down color-caret"></i>
                                </div>
                            </div>
                            <div id="choose-appliance" class="appliance-list-selector col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <label class="lbl-choose-appliances">Select the host device:</label>
                                <br>
                                <!--
<ul id="appliance-list" class="ul-appliance-list">
<?php
$sql = "select * from Hosts";
$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
    $hID = $row['Host_ID'];
    $hN = $row['Host_Name'];
    echo '<li id="comp-app-' . $hID . '" class="li-appliance" onclick="fnSelectCompHost(this.id)">' . $hN . '</li>';
}
?>
</ul>
-->
                                <select id="newCompHostSelect" class="selects color-select">
                                    <option>Select a host...</option>
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <img id="PiImg" src="Images/layouts/pi_third.png" class="center-block dont-display" usemap="#pi-map">

                                <map name="pi-map">
                                    <area alt="14" title="14" href="javascript:fnNewAppPinSet(14)" coords="144,29,6" shape="circle">
                                    <area alt="15" title="15" href="javascript:fnNewAppPinSet(15)" coords="163,28,7" shape="circle">
                                    <area alt="18" title="18" href="javascript:fnNewAppPinSet(18)" coords="184,28,8" shape="circle">
                                    <area alt="23" title="23" href="javascript:fnNewAppPinSet(23)" coords="223,28,8" shape="circle">
                                    <area alt="24" title="24" href="javascript:fnNewAppPinSet(24)" coords="243,28,9" shape="circle">
                                    <area alt="25" title="25" href="javascript:fnNewAppPinSet(25)" coords="282,28,8" shape="circle">
                                    <area alt="8" title="8" href="javascript:fnNewAppPinSet(8)" coords="304,28,8" shape="circle">
                                    <area alt="7" title="7" href="javascript:fnNewAppPinSet(7)" coords="323,28,8" shape="circle">
                                    <area alt="12" title="12" href="javascript:fnNewAppPinSet(12)" coords="383,29,7" shape="circle">
                                    <area alt="16" title="16" href="javascript:fnNewAppPinSet(16)" coords="423,28,9" shape="circle">
                                    <area alt="20" title="20" href="javascript:fnNewAppPinSet(20)" coords="443,29,8" shape="circle">
                                    <area alt="21" title="21" href="javascript:fnNewAppPinSet(21)" coords="462,28,7" shape="circle">
                                    <area alt="2" title="2" href="javascript:fnNewAppPinSet(2)" coords="104,53,8" shape="circle">
                                    <area alt="3" title="3" href="javascript:fnNewAppPinSet(3)" coords="124,52,8" shape="circle">
                                    <area alt="4" title="4" href="javascript:fnNewAppPinSet(4)" coords="144,53,8" shape="circle">
                                    <area alt="17" title="17" href="javascript:fnNewAppPinSet(17)" coords="184,52,9" shape="circle">
                                    <area alt="27" title="27" href="javascript:fnNewAppPinSet(27)" coords="204,53,8" shape="circle">
                                    <area alt="22" title="22" href="javascript:fnNewAppPinSet(22)" coords="224,52,9" shape="circle">
                                    <area alt="10" title="10" href="javascript:fnNewAppPinSet(10)" coords="264,52,8" shape="circle">
                                    <area alt="9" title="9" href="javascript:fnNewAppPinSet(9)" coords="283,52,9" shape="circle">
                                    <area alt="11" title="11" href="javascript:fnNewAppPinSet(11)" coords="303,52,9" shape="circle">
                                    <area alt="5" title="5" href="javascript:fnNewAppPinSet(5)" coords="363,53,9" shape="circle">
                                    <area alt="6" title="6" href="javascript:fnNewAppPinSet(6)" coords="383,53,8" shape="circle">
                                    <area alt="13" title="13" href="javascript:fnNewAppPinSet(13)" coords="403,54,7" shape="circle">
                                    <area alt="19" title="19" href="javascript:fnNewAppPinSet(19)" coords="422,53,7" shape="circle">
                                    <area alt="26" title="26" href="javascript:fnNewAppPinSet(26)" coords="443,52,9" shape="circle">
                                </map>

                                <img id="ESPImg" class="center-block dont-display" src="Images/layouts/ESP8266_375.png" usemap="#ESP-map">

                                <map name="ESP-map">
                                    <area alt="16" title="16" href="javascript:fnNewAppPinSet(16)" coords="107,35,215,62" shape="rect">
                                    <area alt="5" title="5" href="javascript:fnNewAppPinSet(5)" coords="109,66,215,90" shape="rect">
                                    <area alt="4" title="4" href="javascript:fnNewAppPinSet(4)" coords="109,93,213,118" shape="rect">
                                    <area alt="0" title="0" href="javascript:fnNewAppPinSet(0)" coords="109,121,213,147" shape="rect">
                                    <!-- <area alt="2" title="2" href="javascript:fnNewAppPinSet(2)" coords="109,151,213,176" shape="rect"> -->
                                    <area alt="14" title="14" href="javascript:fnNewAppPinSet(14)" coords="108,238,213,261" shape="rect">
                                    <area alt="12" title="12" href="javascript:fnNewAppPinSet(12)" coords="109,269,213,291" shape="rect">
                                    <area alt="13" title="13" href="javascript:fnNewAppPinSet(13)" coords="110,299,212,320" shape="rect">
                                    <area alt="15" title="15" href="javascript:fnNewAppPinSet(15)" coords="110,328,211,348" shape="rect">
                                    <area alt="3" title="3" href="javascript:fnNewAppPinSet(3)" coords="106,353,212,377" shape="rect">
                                    <area alt="1" title="1" href="javascript:fnNewAppPinSet(1)" coords="104,382,213,406" shape="rect">
                                </map>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"> 
                                    <i class="fa fa-home fa-login"></i>
                                    <input type="text" id="pin-number" placeholder="Pin Number" class="input-login scene-name-input center" disabled="true">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <p id="new-comp-pin-text"></p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <button type="button" class="btn-component-save-cancel btn-setting-option btn-save-appliance" onclick="fnSaveAppliance()">Save</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>

        <div id="tempModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" onclick="fnClearSceneModal()">&times;</button>
                        <h4 class="modal-title">Temperature Settings</h4>
                    </div>
                    <!-- Modal body-->
                    <div class="modal-body">
                        <!-- Modal form-->
                        <form id="temp-form" class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="automate-temp-div">
                                    <label class="lbl-automate">Would you like to set a temperature for automated cooling?</label>

                                    <label style="margin: 0 5px;">Yes</label>
                                    <input type="radio" style="margin: 0 5px;" id="yesTempAutomated" name="tempAutomate" value="1" onclick="fnHideShowTempAutomation(this.value)"/>

                                    <label style="margin: 0 5px;">No</label>
                                    <input type="radio" style="margin: 0 5px;" id="noTempAutomated" name="tempAutomate" value="0" onclick="fnHideShowTempAutomation(this.value)" checked />
                                </div>
                            </div>
                            <div id="automate-temp" class="dont-display automate-temp-div col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    Temperature
                                    <input type="number" placeholder="Temperature" style="margin-left: 10px;">
                                </div>
                                <label style="margin: 0 5px;">°F</label>
                                <input type="radio" style="margin: 0 5px;" id="tempF" name="tempType" value="F" checked/>
                                <label style="margin: 0 5px;">°C</label>
                                <input type="radio" style="margin: 0 5px;" id="tempC" name="tempType" value="C" />
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="button" id="btn-save-temp-settings" class="btn-component-save-cancel btn-setting-option btn-save-appliance" style="margin-top: 15px;">Save</button>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>

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
                    if ($F == '' || $C == '') {
                        echo '<span id="displayTempF" class="temp-display">N/A</span>';
                        echo '<span id="displayTempC" class="temp-display-off">N/A</span>';
                    } else {
                        echo '<span id="displayTempF" class="temp-display">' . $F . '°</span>';
                        echo '<span id="displayTempC" class="temp-display-off">' . $C . '°</span>';
                    }
                    ?>

                    <button id="btnTempF" class="btn-temp btn-temp-left btn-temp-selected" onclick="fnTempChange(this.id)">F</button>
                    <button id="btnTempSettings" class="btn-temp btn-temp-settings" data-toggle="modal" data-target="#tempModal"><i class="fa fa-cog"></i></button>
                    <button id="btnTempC" class="btn-temp btn-temp-right btn-temp-not-selected" onclick="fnTempChange(this.id)">C</button>
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
                    <div class="dropdown">
                        <button class="dropdown-toggle btn-new-component" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            New<i class="fa fa-plus fa-plus-main"></i>
                        </button>
                        <div class="dropdown-menu new-dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item new-dropdown" data-toggle="modal" data-target="#newCompModal">Appliance</a>
                            <a class="dropdown-item new-dropdown" data-toggle="modal" data-target="#myModal" onclick="fnClearSceneModal()">Scene</a>
                        </div>
                    </div>
                </div>

                <div style="display: inline-block; margin-top: 10px;">
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
                    <div style="margin-top: -6px; float: right;">
                        <button class="fa fa-sign-out-alt btn-sign-out" onclick="phpLogout()"></button>
                        <!--                        <button class="fa fa-cog btn-sign-out btn-cog" onclick="window.location.href='house-settings.php'"></button>-->
                        <?php

                        if ($_SESSION['gID'] == 1) {
                            echo '<button class="fa fa-users btn-sign-out btn-cog" onclick="window.location.href=\'groupSettings.php\'"></button>';
                        }
<<<<<<< HEAD

                        if ($_SESSION['gID'] != 2) {
                            echo '<button class="fa fa-home btn-sign-out btn-cog" onclick="window.location.href=\'house-settings.php\'"></button>';
                        }

=======
                        
>>>>>>> 00918fdedc7a7853bebc74877c7743b3561b4258
                        echo '<button class="fa fa-user btn-sign-out btn-cog" onclick="window.location.href=\'accountSettings.php\'"></button>';
                        
                         if ($_SESSION['gID'] != 2) {
                            echo '<button class="fa fa-home btn-sign-out btn-cog" onclick="window.location.href=\'house-settings.php\'"></button>';
                        }
                        ?>

                    </div>
                </div>
            </div>

            <hr>
            <!--                    SCENES-->
            <div id="all-scenes" class="all-scenes-container">
                <div class="scene-container">
                    <button id="" class="scene-name all-on-off" type="button" disabled>All
                        <button id="all-off" class="btn-scene-settings all-off" onclick="fnAllOff()">Off</button>
                        <button id="all-on" class="btn-scene-settings all-on" onclick="fnAllOn()">On</button>
                    </button>
                </div>
                <?php
                $sql = "SELECT Scene_ID, Scene_Name, Scene_Order, Scene_Color FROM Scenes WHERE House_ID = " . $_SESSION['home'] . "";
                $result = mysqli_query($conn,$sql);
                $i = -1;
                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    $sID = $row['Scene_ID'];
                    $sN = $row['Scene_Name'];
                    $sO = $row['Scene_Order'];
                    $sC = $row['Scene_Color'];


                    echo '<div class="scene-container">
                                        <button id="scene-' . $sID . '" class="scene-name scene-' . $sC . '" onclick="fnSetScene(this.id)">' . $sN . '
                                            <button id="scene-' . $sID . '" class="btn-scene-settings" onclick="fnSceneSettings(this.id)">
                                                <i class="fa fa-cog fa-setting-scene"></i>
                                            </button>
                                        </button>
                                    </div>';
                }
                ?>
            </div>
            <!--                    END SCENES-->

            <div id="all-components">
                <?php
                $sql3 = "SELECT Addon.Addon_Name, Addon.Addon_Description, Addon.Addon_ID, A.Room_Name, Addon.Addon_State, Addon.Addon_Type 
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
                    $aT = $row3['Addon_Type'];
                    $strippedrN=preg_replace('/\s+/', '', $rN);

                    //Sets variables based on Addon_State
                    if ($aS > '0') {
                        $buttonClass = "btn-component-switch btn-on";
                        $buttonText = "On";
                    } else{
                        $buttonClass = "btn-component-switch btn-off";
                        $buttonText = "Off";
                    }

                    //dynamically adds component divs
                    if ($aT == "L")
                    {
                        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN .'" id="' . $strippedrN . '">
                                <div class="component-card">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <p class="p-component-main"><i id="l-image-' . $aID .'" class="fa fa-lightbulb" style="margin-right: 20px;"></i>' . $aN . '</p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="'. $buttonClass . '" id="l' . $aID . '" onclick="fnSwitchClick(this.id)">'. $buttonText . '</button>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <p class="p-component-label"><b>Room:</b> ' . $rN . '</p>
                                        <p class="p-component-label"><b>Description:</b> ' . $aD . '</p>

                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn-component-switch btn-component-switch-settings fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                    </div>
                                </div>
                              </div>';
                    }
                    else if ($aT == "S")
                    {
                        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN . '" id="' . $strippedrN . '">
                                <div class="component-card-slider">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div class="row">
                                            <p class="p-component-main"><img id="s-image-' . $aID .'" class="slider-icon" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHdpZHRoPSIxNnB4IiBoZWlnaHQ9IjE2cHgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CjxwYXRoIGZpbGw9IiNmZmQ1MDAiIGQ9Ik0xNiA2aC0zLjZjLTAuNy0xLjItMi0yLTMuNC0ycy0yLjggMC44LTMuNCAyaC01LjZ2NGg1LjZjMC43IDEuMiAyIDIgMy40IDJzMi44LTAuOCAzLjQtMmgzLjZ2LTR6TTEgOXYtMmg0LjFjMCAwLjMtMC4xIDAuNy0wLjEgMXMwLjEgMC43IDAuMSAxaC00LjF6TTkgMTFjLTEuNyAwLTMtMS4zLTMtM3MxLjMtMyAzLTMgMyAxLjMgMyAzYzAgMS43LTEuMyAzLTMgM3oiLz4KPC9zdmc+Cg==" />' . $aN . '</p>
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
                                            <button class="btn-component-switch btn-component-switch-settings fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                        </div>
                                    </div>

                                </div>
                            </div>'; 
                    }
                    else if ($aT == "F")
                    {
                        echo'<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN . '" id="' . $strippedrN . '">
                                <div class="component-card">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="margin-top: 7px;">
                                        <p class="p-component-main"><img id="f-image-' . $aID .'" class="fan-icon" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI5NS4xODIgMjk1LjE4MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjk1LjE4MiAyOTUuMTgyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPHBhdGggaWQ9IlhNTElEXzNfIiBkPSJNMjAwLjQ5OSwxMjQuNGM3Ljk3LTIuNzk3LDE2LjMxOC0xLjQ3NCwyNS4yNTgsMC40OWM1LjkwMSwxLjMsMTIuMDA2LDIuNjQ4LDE4Ljg1NCwyLjY0OCAgYzkuMTY1LDAsMTcuNzQ1LTIuNTEsMjYuMjMyLTcuNjY5YzEzLjA4MS03Ljk1NCwyMi4wMy0yMi4xODMsMjMuOTQtMzguMDYxYzIuMTc4LTE4LjE0My00LjYxOS0zNi4xNDItMTkuMTQ2LTUwLjY2NyAgYy0xNC40Mi0xNC40Mi0zMy42Ny0yMi4zNTctNTQuMjAzLTIyLjM1N2MtMjAuOTU1LDAtNDAuNzE1LDguMjItNTUuNjQyLDIzLjE0M2MtMTguNjU2LDE4LjY1Ny0zMC43OTMsNDguNDAzLTMxLjAwMyw3NS42ODQgIGMtNC40MzktMy4yMzEtOC4zNy03LjE4OS0xMC4zODktMTIuOTI5Yy0zLjEyNS04Ljg3Ny0xLjQ0OS0xNi40NjksMC40ODgtMjUuMjU5YzIuNjU4LTEyLjA0LDUuOTYtMjcuMDI1LTUuMDE5LTQ1LjA4NyAgQzExMC43NDMsOS4zMzQsOTMuNjU3LDAuMDExLDc1LjI3NCwwLjAxMWMtMTUuODU4LDAtMzEuNTMsNi45MzgtNDQuMTMxLDE5LjUzNEMxLjA3Niw0OS42MjIsMS40MzYsOTguODk4LDMxLjkzNCwxMjkuMzkgIGMxOC40MTEsMTguNDE2LDQ4LjY2NSwzMC44MTYsNzUuNjAxLDMxLjExOWMtMy4yMjEsNC4zOTUtNy4xNTksOC4yNjYtMTIuODUxLDEwLjI3MmMtNy45NTcsMi44MDgtMTYuMzA2LDEuNDkzLTI1LjI2MS0wLjQ4OSAgYy01Ljg5OS0xLjI5OS0xMi4wMDQtMi42NDktMTguODU2LTIuNjQ5Yy05LjE2MSwwLTE3Ljc0MiwyLjUwOC0yNi4yMjgsNy42NjdjLTEzLjA4MSw3Ljk1Ni0yMi4wMywyMi4xODQtMjMuOTQsMzguMDYzICBjLTIuMTc5LDE4LjE0Myw0LjYyMiwzNi4xNDMsMTkuMTQ1LDUwLjY2NmMxNC40MTksMTQuNDIsMzMuNjcxLDIyLjM1Niw1NC4yMDUsMjIuMzU2YzIwLjk1NCwwLDQwLjcxNi04LjIyLDU1LjY0Mi0yMy4xNDIgIGMxOC42NTYtMTguNjU3LDMwLjc5Mi00OC40MDIsMzEuMDA0LTc1LjY4M2M0LjQzNiwzLjIzLDguMzY3LDcuMTg5LDEwLjM4OSwxMi45MjdjMy4xMjQsOC44NzcsMS40NDksMTYuNDY5LTAuNDksMjUuMjYgIGMtMi42NTgsMTIuMDM5LTUuOTYsMjcuMDI1LDUuMDIsNDUuMDg3YzkuMTI2LDE1LjAwNCwyNi4yMTMsMjQuMzI3LDQ0LjU5NiwyNC4zMjdjMTUuODU5LDAsMzEuNTMxLTYuOTM5LDQ0LjEzMi0xOS41MzQgIGMzMC4wNjEtMzAuMDcxLDI5LjcwOC03OS4zNDctMC43OTItMTA5Ljg0NWMtMTguNDExLTE4LjQxOC00OC42NjYtMzAuODE3LTc1LjU5OS0zMS4xMTggIEMxOTAuODY5LDEzMC4yNzcsMTk0LjgwOSwxMjYuNDA2LDIwMC40OTksMTI0LjR6IE0xNjAuMTg3LDE2MC4xODdjLTYuNzI3LDYuNzI3LTE4LjQ2NSw2LjcyNy0yNS4xOTIsMCAgYy02Ljk0My02Ljk0OC02Ljk0My0xOC4yNDQsMC0yNS4xOTJjMy4zNjMtMy4zNjMsNy44MzYtNS4yMTUsMTIuNTk2LTUuMjE1YzQuNzYxLDAsOS4yMzIsMS44NTIsMTIuNTk3LDUuMjE1ICBDMTY3LjEyOSwxNDEuOTQzLDE2Ny4xMjksMTUzLjIzOSwxNjAuMTg3LDE2MC4xODd6IiBmaWxsPSIjMGE5MWM3Ii8+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />' . $aN . '</p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="'. $buttonClass . '" id="f' . $aID . '" onclick="fnSwitchClick(this.id)">'. $buttonText . '</button>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <p class="p-component-label"><b>Room:</b>  ' . $rN . '</p>
                                        <p class="p-component-label"><b>Description:</b> ' . $aD . '</p>

                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn-component-switch btn-component-switch-settings fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                    </div>
                                </div>
                            </div>';
                    }
                }
                ?> 
            </div>
        </div>
    </body>    
</html>

