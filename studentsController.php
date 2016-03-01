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
        case 'students':
        	getStudents($test_controller);
        	break;
        case 'delete':
        	deleteStudent($test_controller);
        	break;
        case 'student':
        	getStudent($test_controller);
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

function getStudents($controller){
	echo json_encode($controller->get_students());
	exit;
}

function getStudent($controller){
	echo json_encode($controller->get_student($_POST['id']));
	exit;
}

function deleteStudent($controller){
	echo $controller->delete_ID_from_table("studentID=" . (string)$_POST['id'], "students");
}

function edit($controller) {
	echo $controller->edit_student($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['email']);
	exit;
}

function insert($controller) {
	echo $controller->insert_student($_POST['firstname'], $_POST['lastname'], $_POST['email']);
	exit;
}
?>
