<?php

class controller {

    var $servername		= "localhost"; 
    var $username		= "root";
    var $password		= "";
    var $database_name 	= "";
    var $conn;
    var $connected = FALSE;

    public function init($server, $user, $p, $d){
		$this->servername    = $server;
		$this->username	     = $user;
		$this->password	     = $p;
		$this->database_name = $d;
		$result = $this->init_database($d);
		if($result){
			$this->connected = $this->connect();
		}
		if($this->connected){
			$this->init_tables();
		}
	}

    function init_database($dbname){
    	$conn;
    	try{
	    	$conn = new PDO("mysql:host=$this->servername;",$this->username,$this->password);
	    	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e){
	    	echo "Database init failed: " . $e->getMessage();
	    	return FALSE; 
		}
    	$query = "CREATE DATABASE IF NOT EXISTS " . $dbname;
    	try { 
			$conn->exec($query);
			$conn = null;
			return true; 
		} 
		catch(PDOException $e) {
			echo $query . "<br>" . $e->getMessage();
			$conn = null; 
			return false;
		} 
    }

    public function toString(){
		echo $this->servername . "\n";
		echo $this->username . "\n";
		echo $this->password . "\n";
    }

    private function connect(){
		try{
	    	$this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database_name",$this->username,$this->password);
	    	$this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	    	return TRUE;
		}
		catch(Exception $e){
	    	echo "Connection failed: " . $e->getMessage();
	    	return FALSE; 
		}
    }
    
    public function entry_to_string($pdo, $entry){
    	$result = "";
    	$l = $pdo->fetchAll(PDO::FETCH_ASSOC);
    	while (list($key, $value) = each($l[$entry])) {
    		if($result != ""){
    			$result = $result . " ,";
    		}
    		$result = $result .  "$key=$value";
		}
		return $result;
    }
    
    public function table_exists($table_name){
		if($this->connected != TRUE) return FALSE;
		if ($this->conn->query("SHOW TABLES LIKE '" . $table_name . "'")->rowCount() > 0){
	    	return TRUE;
		}
		return FALSE;
    }
    
    private function init_tables(){
    	include('table_defs.php');
    	foreach($tables_list as $key => $value) {
    		if(!$this->table_exists($key)){
    			$this->conn->exec($value);
    		}
		}
    }
    
    //##########---STUDENT FUNCTIONS---################
    /*
    insert_student(first, last, email)
    
    Adds a new student record to the database
    @param $first (string) First name of student.
    @param $last (string) Last name of the student.
    @param $email (string) Email of student.
    @post If successfull a new student is added to the database.
    @return None.
    */
    public function insert_student($first, $last, $email){
		if(!$this->connected) return;
		$sql = " INSERT INTO students(firstName, lastName,email) VALUES ('$first','$last','$email')";
		try { 	
			$this->conn->exec($sql); 
		} 
		catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage(); 
		} 
	}
	/*
    get_students()
    
    Returns all values from student table
    @return PDOStatement object containing all student records..
    */
	public function get_students(){
		$q = "SELECT * FROM students";
		if(!$this->connected) return;
		return $this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public function edit_student($id, $first, $last, $email){
		if(!$this->connected) return;
		$sql = "UPDATE students SET firstName=\"$first\", 
			lastName=\"$last\",
			email=\"$email\"
			WHERE studentID=$id";
		try { 	
			$this->conn->exec($sql); 
		} 
		catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage(); 
		} 
	}
	
	/*
    get_student($stuID)
    
    Returns record of student
    @param $stuID (int) Unique id of student record.
    @return PDOStatement object of specified student.
    */
	public function get_student($stuID){
		$q = "SELECT * FROM students WHERE students.studentID=$stuID";
		if(!$this->connected) return;
		return $this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	// ############# Achievements functions ###################
	/*
		
	*/
	public function insert_achievement($name, $short, $long, $points, $cat){
		if(!$this->connected) return;
		$sql = " INSERT INTO achievements(name, short_desc,long_desc, points, catagory) 
			VALUES ('$name', '$short', '$long', '$points', '$cat')";
		try { 	
			$this->conn->exec($sql); 
		} 
		catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage(); 
		} 
	}
	
	public function edit_achievement($id, $name, $short, $long, $points, $cat){
		if(!$this->connected) return;
		$q = "UPDATE achievements SET name=\"$name\", 
			short_desc=\"$short\",
			long_desc=\"$long\", 
			points=$points,
			catagory=\"$cat\"
			WHERE achievementID=$id";
		try { 	
			$this->conn->exec($q); 
		} 
		catch(PDOException $e) {
			echo $q . "<br>" . $e->getMessage(); 
		} 
	}
	
	public function get_achievements(){
		$q = "SELECT * FROM achievements";
		if(!$this->connected) return;
		return $this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function earn_achievement($achID, $stuID){
		if(!$this->connected) return;
		$q = "INSERT INTO achievements_earned(achievementID, studentID) VALUES($achID, $stuID)";
		try { 	
			$this->conn->exec($q); 
		} 
		catch(PDOException $e) {
			echo $q . "<br>" . $e->getMessage(); 
		} 
	}
	
	function get_earned_ach_student($stuID){
		$q = "SELECT * FROM achievements_earned LEFT JOIN achievements AS a1 
			ON achievements_earned.achievementID=a1.achievementID 
			LEFT JOIN students AS s 
			ON achievements_earned.studentID = s.studentID
			WHERE s.studentID=$stuID";
		if(!$this->connected) return;
		//echo(json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)));
		return $this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function get_total_points_for_student($stuID){
		$q = "SELECT SUM(points) AS total FROM achievements_earned LEFT JOIN achievements AS a1 
			ON achievements_earned.achievementID=a1.achievementID 
			LEFT JOIN students AS s 
			ON achievements_earned.studentID = s.studentID
			WHERE s.studentID=$stuID";
		if(!$this->connected) return;
		//echo(json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)));
		return $this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//#########---- DELETE ID FROM TABLE --- ############	
	function delete_ID_from_table($condition, $tablename){
		if(!$this->connected) return;
		$q = "DELETE FROM $tablename WHERE $condition";
		try { 	
			$this->conn->exec($q);
			echo $q; 
		} 
		catch(PDOException $e) {
			echo $q . "<br>" . $e->getMessage(); 
		} 
	}	
	
	//#########---- UPDATE ID FROM TABLE --- ############	
	
	function update_ID_from_table($ID, $tablename){
		if(!$this->connected) return;
		$q = "DELETE FROM $tablename WHERE $condition";
				try { 	
			$this->conn->exec($q); 
		} 
		catch(PDOException $e) {
			echo $q . "<br>" . $e->getMessage(); 
		} 
	}
}
?>
