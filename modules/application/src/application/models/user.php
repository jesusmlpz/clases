<?php

/**
 * 
 */
class User {
	public $iduser;
	public $name;
	public $email;
	public $password;
	public $description;
	public $photo;
	public $bdate;
	public $cities_idcity;
	public $genders_idgender;
	public $hobbies = array();
	
	public function __construct ($row) {
		$this->iduser = $row['iduser'];
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->hobbies = explode(',', $row[3]);
		
	}
	
	public function userToStr() {
		$user_str = $this->iduser . "|" . $this->name . 
					"|" . $this->email . "|";
		if (count($this->hobbies)>0) {
			$user_str .= "|" . implode(",", $this->hobbies);
		}
		return $user_str;
	}
}

/**
 * 
 */
class UserCollection {
	public $users = array();
	
	public function __construct($config) {
		$mysql = new PDO('mysql:host='.$config['db']['host'].
							';dbname='.$config['db']['database'],
							$config['db']['user'],
							$config['db']['password']);

		$query = "SELECT iduser, name, email, group_concat(hobbies.hobby)
                FROM users 
                LEFT JOIN users_has_hobbies 
                ON users_iduser = users.iduser 
                LEFT JOIN hobbies 
                ON hobbies_idhobby = hobbies.idhobby
                GROUP BY iduser";
				
		$result = $mysql->query($query);

		// (SELECT) Recorrer el recordset
    	while($row = $result->fetch())
	    {
    		// crear usuario
    		$this->users[] = new User($row);
	    }    
		$mysql = null;
	}
	
	public function usersToArray() {
		for ($i=0; $i < count($this->users); $i++) { 
			$users_array[] = $this->users[$i]->userToStr();
		}
		
		return $users_array;		
	} 
}

