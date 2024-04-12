<?php 

header('Content-Type: text/html; charset=utf-8');
// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "panel.wear4safe_eu_db_dbuser"; 
$dbPassword = "s4m54kL%4"; 
$dbName     = "panelwear4safe_eu_db"; 

//mysql_set_charset('utf8');

// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
mysqli_set_charset($db,'utf8'); 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}
mysqli_set_charset('utf8');
/**/
echo '123';


if(!empty($_POST["profession_id"])){ 
    //$query = "SELECT * FROM professionsmap WHERE profession_id = ".$_POST['profession_id']." AND is_valid='True' "; 
	$query = "SELECT * FROM professionsmap t1 INNER JOIN maptypes t2 ON t1.maptype_id=t2.maptype_id WHERE 1=1 AND profession_id = ".$_POST['profession_id']." AND t1.is_valid='True';";
    $result = $db->query($query); 
     
    if($result->num_rows > 0){ 
        echo '<option value="0">Επιλογή</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['maptype_id'].'" '.'>'.$row['maptype_name'].'</option>';
        } 
    } else{ 
        echo '<option value="">Μη διαθέσιμο</option>'; 
    } 
}
/**/

?>