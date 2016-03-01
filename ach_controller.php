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
        case 'edit':
        	edit($test_controller);
        	break;
    }
}

function select() {
    echo "The select function is called.";
    exit;
}

function getAch($controller){
	//console.log("GETING ACH");
	echo json_encode($controller->get_achievements());
	exit;
}

function deleteAch($controller){
	echo $controller->delete_ID_from_table("achievementID=" . (string)$_POST['id'], "achievements");
}

function edit($controller){
	//console.log("INSERT CALLED");
	$id  = $_POST['id'];
	$name = $_POST['name'];
	$short = $_POST['short'];
	$long = $_POST['long'];
	$points = $_POST['points'];
	$cat = $_POST['category'];
	echo $controller->edit_achievement($id, $name, $short, $long, $points, $cat);
	exit;
}

function insert($controller) {
	//console.log("INSERT CALLED");
	$name = $_POST['name'];
	$short = $_POST['short'];
	$long = $_POST['long'];
	$points = $_POST['points'];
	$cat = $_POST['category'];
	echo $controller->insert_achievement($name, $short, $long, $points, $cat);
	exit;
}
?>
