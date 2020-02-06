<?php
//header('content-type: application/json; charset=utf8;'); 
//ini_set('max_execution_time', 300);
ini_set('display_errors', 1);
error_reporting(E_ALL);
//Localhost
/**include("config/constant.php"); 
include("class/class.mysqli.php");
include("class/class.functions.php"); **/
// Server

include("/var/www/htmlphp/api/hamstring/config/constant.php"); 
include("/var/www/htmlphp/api/hamstring/class/class.mysqli.php");
include("/var/www/htmlphp/api/hamstring/class/class.functions_dev.php"); 

$action = strtolower(trim($_GET['action']));
if ((isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == AUTH_USERNAME) && (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_PW'] == AUTH_PASSWORD)) {
	// AUTHENTICATED REQUEST
} else {
	$returnArray = array(
		"result" => FALSE,
		"message" => AUTH_FAIL
	);
	echo json_encode($returnArray);
	exit;
}  

$objFunctions = new Functions();
$return   = array(); 
/**$data = array();
if(isset($_REQUEST['json']))
{	
	$json = $_REQUEST['json'];
	$data = json_decode($json, true);
}**/
$json = @file_get_contents('php://input');
$data = json_decode($json, true);
// here we decide which function to be called for the request type
switch ($action) {
	case 'test_func':
		$return = $objFunctions->testFunc();
		//echo 'Diff : '.$return;
		//exit;
		break;
	case 'read_external_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->getSharedAtheleteInfoMultiple($data);
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_data_count':
		if(!empty($data)){
		 	$return = $objFunctions->readDataCount($data);
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_business':
		if(!empty($data)){
		 	$return = $objFunctions->deleteAndReadSessionHeader($data);
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'url_settings':
		$return = $objFunctions->getURLSettings();
		break;
	case 'get_server_time':
		$return = $objFunctions->getServerTimings();
		break;
	case 'send_base64_blob':
		if(!empty($data)){
		 	$return = $objFunctions->sendBase64Blob($data);
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'create_pri_parent':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_Parent');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_parent':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_Parent');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_parent':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_Parent');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_parent':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_Parent');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_parent':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_Parent');
		break;
	
	case 'create_pri_parent_user':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_ParentUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_parent_user':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_ParentUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_parent_user':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_ParentUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_parent_user':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_ParentUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_parent_user':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_ParentUser');
		break;
		
	case 'create_pri_business':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_Business');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_business':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_Business');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_business':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_Business');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_business':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_Business');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_business':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_Business');
		break;
		
	case 'create_pri_user':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_User');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_user':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_User');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_user':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_User');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_user':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_User');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_user':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_User');
		break;
		
	case 'create_pri_team':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_Team');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_team':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_Team');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_team':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_Team');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_team':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_Team');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_team':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_Team');
		break;
		
	case 'create_pri_team_user':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_TeamUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_team_user':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_TeamUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_team_user':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_TeamUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_team_user':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_TeamUser');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_team_user':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_TeamUser');
		break;
		
	case 'create_pri_config_value':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_ConfigurationValue');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_config_value':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_ConfigurationValue');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_config_value':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_ConfigurationValue');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_config_value':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_ConfigurationValue');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_config_value':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_ConfigurationValue');
		break;
		
	case 'create_pri_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_Athlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_Athlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_Athlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_Athlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_athlete':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_Athlete');
		break;
		
	case 'create_pri_team_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_TeamAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_team_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_TeamAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_team_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_TeamAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_team_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_TeamAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_team_athlete':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_TeamAthlete');
		break;
		
	case 'create_pri_share_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_ShareAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_share_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_ShareAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_share_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_ShareAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_share_athlete':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_ShareAthlete');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_share_athlete':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_ShareAthlete');
		break;
		
	case 'create_pri_excercise_type':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_ExerciseType');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_excercise_type':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_ExerciseType');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_excercise_type':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_ExerciseType');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_excercise_type':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_ExerciseType');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_excercise_type':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_ExerciseType');
		break;
		
	case 'create_pri_session_header':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_SessionHeader');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_session_header':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_SessionHeader');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_session_header':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_SessionHeader');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_session_header':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_SessionHeader');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_session_header':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_SessionHeader');
		break;
		
	case 'create_pri_session_header_test':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_SessionHeader_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_session_header_test':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_SessionHeader_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_session_header_test':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_SessionHeader_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_session_header_test':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_SessionHeader_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_session_header_test':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_SessionHeader_test');
		break;
		
	case 'create_pri_session_data':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_SessionData');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_session_data':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_SessionData');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_session_data':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_SessionData');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_session_data':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_SessionData');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_session_data':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_SessionData');
		break;
		
	
	case 'create_pri_session_data_test':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_SessionData_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_session_data_test':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_SessionData_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_session_data_test':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_SessionData_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_session_data_test':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_SessionData_test');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_session_data_test':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_SessionData_test');
		break;
		
		
	case 'create_pri_session_rep':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_SessionRep');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_session_rep':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_SessionRep');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_session_rep':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPri_SessionRep');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_session_rep':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPri_SessionRep');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_session_rep':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_SessionRep');
		break;
		
	case 'create_pub_excercise':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPub_ExerciseTypeDefault');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pub_excercise':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPub_ExerciseTypeDefault');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pub_excercise':
		if(!empty($data)){
		 	$return = $objFunctions->updateTable($data,'tPub_ExerciseTypeDefault');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pub_excercise':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTable($data,'tPub_ExerciseTypeDefault');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pub_excercise':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPub_ExerciseTypeDefault');
		break;
		
	case 'create_pri_log':
		if(!empty($data)){
		 	$return = $objFunctions->insertIntoTable($data,'tPri_Log');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'save_pri_log':
		if(!empty($data)){
		 	$return = $objFunctions->saveIntoTable($data,'tPri_Log');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'modify_pri_log':
		if(!empty($data)){
		 	$return = $objFunctions->updateTableWithID($data,'tPri_Log');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'delete_pri_log':
		if(!empty($data)){
		 	$return = $objFunctions->deleteFromTableWithID($data,'tPri_Log');
		 }
		 else{ 
		 	$return = array('success'=>'0','message'=>CANNOT_BLANK);
		 }
		break;
	case 'read_pri_log':
		$return = $objFunctions->readTableDataForMultipleUUIDs($data,'tPri_Log');
		break;
	case 'read_data':
		$return = $objFunctions->readAllData($data);
		break;
	case 'read_pri_business_multiple':
		$return = $objFunctions->readBusinessDataForMultipleUUIDs($data);
		break;
		
		
	default:
		$return = array('result'=>FALSE,'message'=>'INVALID REQUEST');
		break;
} 
$return['serverTs']=round(microtime(true) * 1000);
echo trim(json_encode($return)); // convert array into json 
exit;
?>
