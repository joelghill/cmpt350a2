<?php

	$create_student_table = "CREATE TABLE students(
		studentID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		lastName varchar(255),
		firstName varchar(255),
		email varchar(255),
		reg_date TIMESTAMP
		)";
		
	$create_achievements_table = "CREATE TABLE achievements(
		achievementID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255),
		short_desc VARCHAR(255),
		long_desc VARCHAR(400),
		points int,
		catagory varchar(255),
		creation_date TIMESTAMP
		)";
		
	$create_achievements_earned_table = "CREATE TABLE achievements_earned(
		earnedID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		achievementID int UNSIGNED,
		studentID int UNSIGNED,
		acheived_date TIMESTAMP
		)";
	
	$tables_list = [
		"students" => $create_student_table,
		"achievements" => $create_achievements_table,
		"achievements_earned" => $create_achievements_earned_table
	];
	
?>
