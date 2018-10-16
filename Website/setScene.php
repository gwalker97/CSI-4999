<?php
    require('config.php');

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $hID = $_SESSION['home'];
    
    $sql = "UPDATE Scene_Assignment SET IsSet = 0 WHERE Scene_ID != '$id'";
    $result = $conn->query($sql);
    $sql = "UPDATE Addon SET Addon_State = 0.00 WHERE Addon_House_ID = '$hID'";
    $result = $conn->query($sql);

    
    $Addon_IDs = array();
    $i = 0; 
    $sqlSetScene = "";
    $sqlSetAddons = "";
    $sql = "SELECT Addon_ID, IsSet FROM Scene_Assignment WHERE Scene_ID = '$id'";
    $result = $conn->query($sql);
    $num_rows = $result->num_rows;

    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $i++;
        $isSet = $row['IsSet'];
        $Addon_ID = $row['Addon_ID'];
        array_push($Addon_IDs, $Addon_ID);
        
        if ($isSet == 1)
        {
            $sqlSetScene = "UPDATE Scene_Assignment SET IsSet = 0 WHERE Scene_ID = '$id'";
        }
        else
        {
            $sqlSetScene = "UPDATE Scene_Assignment SET IsSet = 1 WHERE Scene_ID = '$id'";
        }
        
        if($i == $num_rows)
        {
            if ($isSet == 1) {
                foreach ($Addon_IDs as $aID) {
                    $sql = "UPDATE Addon SET Addon_State = 0.00 WHERE Addon_ID = '$aID'";
                    $result = $conn->query($sql);
                }
            }
            else
            {
                foreach ($Addon_IDs as $aID) {
                    $sql = "UPDATE Addon SET Addon_State = 1.00 WHERE Addon_ID = '$aID'";
                    $result = $conn->query($sql);
                }
            }
        }
    }
    
    $result = $conn->query($sqlSetScene);

    $conn->close();
?>

