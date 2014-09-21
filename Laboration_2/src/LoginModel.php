<?php

//Är användaren inloggad?
//Får användaren logga in?
//Vem är inloggad?

//require_once("/customers/c/4/b/alexandraseppanen.se//httpd.www/src/users.txt");

class LoginModel {
	private $loggedIn = "loggedIn";
	private $username = "";
	
	public function __construct(){
		
	}
	
	public function checkIfEmpty($username, $password){

		if(strlen($username) == 0 or strlen($password) == 0){
			
			if (strlen($username) == 0){
				echo "Användarnamn saknas";
			}
			else if (strlen($password) == 0){
				echo "Lösenord saknas";
			}
			return true;
		}
		
		return false;
	}
	
	
	//Kollar om användaren redan är inloggad eller ej
	public function loggedInStatus(){
			
		if(isset($_SESSION[$this->loggedIn]) == false){
			$_SESSION[$this->loggedIn] = 0;
		}
		
		if(isset($_POST["logOut"]) and $_SESSION[$this->loggedIn] == 1){
            //$this->logOut();

			//echo "Du har nu loggat ut";
		}

		if($_SESSION[$this->loggedIn] == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function userLoggedInStatus(){
		if(isset($_SESSION[$this->loggedIn]) && $_SESSION[$this->loggedIn] == true){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function logOut(){

		setcookie('Username', null, time()-3600);
        setcookie('Password', null, time()-3600);
		
		$storage = fopen("src/storage.txt", "w");
		fclose($storage);
		
		if (ini_get("session.use_cookies")) { 
			$params = session_get_cookie_params(); 
			setcookie(session_name(), '', time() - 42000, 
			    $params["path"], $params["domain"], 
			    $params["secure"], $params["httponly"]); 
		} 
		
		if(session_id() != '') {
            session_unset();
            session_destroy();
        }
	}
	
	public function resetCookies(){
	    if(isset($_COOKIE["Username"]) && isset($_COOKIE["Password"])){
	    	setcookie('Username', null, false);
            setcookie('Password', null, false);
	    }
		$storage = fopen("src/storage.txt", "w");
		fclose($storage);
			if(session_id() != '') {
                session_destroy();
            }
	}
	
	//Hämtar användarnamnet på personen inloggad i sessionen
	public function getLoggedInUser(){
		if(isset($_SESSION["username"])){
			return $_SESSION["username"];
		}
	}
	
	public function setLoggedInStatus(){
		$_SESSION[$this->loggedIn] = 1;
		//echo "Inloggning lyckades";
	}
	
	public function login($username, $password, $value){
		
		if($this->checkIfEmpty($username, $password) == false){
			$_SESSION["username"] = $username;
			$_SESSION["password"] = $password;
		
			$linesArr = array();
			$fh = fopen("src/users.txt", "r");
			
			while (!feof($fh)){
				$line = fgets($fh);
				$line = trim($line);
				
				$linesArr[] = $line;
			}
			fclose($fh);

			for($i = 0; $i < count($linesArr); $i++){
				if($username === $linesArr[$i] and $password === $linesArr[$i+1]){
					if($value){
						echo "Value = true";
					}
					$_SESSION[$this->loggedIn] = true;
					return true;
				}
				else{
					echo "Fel användarnamn eller lösenord";
					return false;
				}
				$i++;
			}
			
		}
	}
}