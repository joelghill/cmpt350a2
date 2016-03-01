<?php
include 'model.php';

if (isset($_POST['action'])) {
	$test_controller = new controller;
	$test_controller->init("localhost", "root", "", "testDatabase");
    switch ($_POST['action']) {
        case 'insert':
            insert($test_controller);
            break;
        case 'select':
            select();
            break;
        case 'achievements':
        	getAch($test_controller);
        	break;
        case 'delete':
        	deleteAch($test_controller);
        	break;
        case 'total':
        	get_total_points($test_controller);
        	break;
    }
}

function select() {
    echo "The select function is called.";
    exit;
}

function get_total_points($controller){
	echo json_encode($controller->get_total_points_for_student($_POST['id']));
	exit;
}

function getAch($controller){
	//console.log("GETING ACH");
	//echo $_POST['id'];
	echo json_encode($controller->get_earned_ach_student($_POST['id']));
	exit;
}

function deleteAch($controller){
	echo $controller->delete_ID_from_table("earnedID=" . (string)$_POST['id'], "achievements_earned");
}

function insert($controller) {
	//console.log("INSERT CALLED");
	$achID = $_POST['achID'];
	$studentID = $_POST['studentID'];
	echo $controller->earn_achievement($achID, $studentID);
	exit;
}
?>
