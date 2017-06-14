<?php
// Get the access token via client credential grant type.
function getToken() {

$ini_array = parse_ini_file("config.ini");
$id = $ini_array['client_id'];
$secret = $ini_array['client_secret'];

$url='https://portal.patchman.co/oauth2/token/';
//$clienttoken=array("client_id" => JFMdhz9yJCJ1lnIPZ3TVqmMHlCR8hhNqodBsmPEl,"client_secret" => MhdVflUow9a6JYzFBIfHPy7CLiUaJSsNp2itr9OCvMPreR2uJ0shqdTYp1fywFGHwyVPAo9lFXqMr4YdrlsG4Bj8ZW54avahLotbDGqosXiXuBM54A40mM8uUeI2xhnh,"grant_type" => "client_credentials");
$clienttoken=array("client_id" => $id,"client_secret" => $secret,"grant_type" => "client_credentials");

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $clienttoken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
//print_r('Curl error: ' . curl_error($ch));
$token = $json['access_token'];
return $token;
}
?>
<?php
// Get this user's ID number
function getUserID($token, $user) {
$url='https://portal.patchman.co/api/v1/endusers/?access_token='.$token.'&username='.$user;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
//print_r('Curl error: ' . curl_error($ch));
$userID = $json[0]['id'];
return $userID;
}
?>
<?php
// Create link to enduser's dashboard
function getandsetRedirect($token, $userID) {
$url='https://portal.patchman.co/api/v1/endusers/'.$userID.'/token/?'.'access_token='.$token;
$clienttoken=array("access_token" => $token);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $clienttoken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
//print_r('Curl error: ' . curl_error($ch));
//var_dump($json);
$link = $json['redirect_to'];
//echo "<a href='$link' target='_blank'>Open dashboard in new tab</a>";
echo "<iframe src='$link' style='width:100%; height:750px; border:0;'><p>Please click on the link.</p></iframe>";
}
?>




<?php
// Get the location
//$loc = $_FILES['file']['tmp_name'];
include ("sanitize.php");

if (isset($_POST['file']) && !empty($_POST['file']) ){

$loc = sanitize($_POST['file']);
$cfile = new CURLFile($loc);

$post_data = array('report_type' => sanitize($_POST['report_type']),'file' => $cfile,'type' => sanitize($_POST['type']), 'description' => sanitize($_POST['description']));
//echo "LOCATION " . $loc;
//var_dump($post_data);

// create connection
$ch = curl_init('https://portal.patchman.co/api/v1/report/');

// set options
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

// set data 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

// do the request
$result = curl_exec($ch);
//var_dump($result);
//echo $result;

$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check if we sent the file successfully
if($status == 201){
   echo '<script> alert( "Thank you for successfully reporting this file." ) </script>';
}
else {
    echo '<script> alert( "The file was not submitted successfully. Please try again." ) </script>';
}

//close the connection
curl_close($ch);

}
?>

