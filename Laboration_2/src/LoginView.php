<?php

//Visualisera data
//Behöver tillgång till datan som den ska visualisera från modellen

class LoginView {
	private $model;
	private $username = "";
	private $rememberValue;
	private $saveUser;
	private $msg ="";
	
	public function __construct(LoginModel $model){
		$this->model = $model;

	}
	
	//Visar login-formuläret om ej redan inloggad
	public function showLoginForm(){
        if(isset($_POST["logOut"])){
            $this->msg = "Du har loggat ut";
        }
		
        if (isset($_POST["username"])){
            $this->username = $_POST["username"];
        }
		//echo $username;
		$ret = "";
		
		setlocale(LC_TIME, "swedish");
		$dateTime = strftime('%A') . ", den " .strftime('%d'). " " .ucfirst(strftime("%B")). " år " .strftime("%Y"). ". Klockan är [" .strftime("%H:%M:%S"). "]";
		$dateTime = ucfirst($dateTime);
		$date = date('l'). " den " .date('jS F') ." år ". date('Y') . ". Klockan är [". date('h:i:s A') . "].";

		$ret = "
		<h1>Laborationskod as223jx</h1>
		<h2>Ej inloggad</h2>
		<form action='' method='post'>
		<fieldset>
		<legend>Login - Skriv in användarnamn och lösenord</legend>
		$this->msg<br>
		Användarnamn: <input type='text' name='username' id='username' value='$this->username'>
		Lösenord: <input type='password' name='password'>
		<input type='checkbox' name='remember' value='Remember'>Håll mig inloggad: 
		<input type='submit' name='submit' value='Logga in'>
		</fieldset>
		</form>
		<p>$dateTime</p>";
		
		return $ret;
	}	
	
	public function setCookie(){
		setcookie('Username', $_POST["username"], time()+60*60*24*365, "/");
		setcookie('Password', crypt($_POST["password"]), time()+60*60*24*365, "/");
		chmod("src/storage.txt", 0777);
		$storage = fopen("src/cookieExpire.txt", "w");
		$data = time()+60*60*24*365;
		fwrite($storage, $data);
		fclose($storage);
	    return;
	}
	
	public function getLoggedInUser(){
		$username = "";
		
		if(isset($_COOKIE["Username"])){
			$this->username = $_COOKIE["Username"];
		}
		else{
			$this->username = $this->model->getLoggedInUser();
		}
		
		return $this->username;
	}
	
	public function getUsername(){
		if(isset($_POST["username"])){
			return $_POST["username"];
		}
	}
	
	public function getPassword(){
		if(isset($_POST["password"])){
			return $_POST["password"];
		}
	}
	
	//Inloggad
	public function showLoggedIn($username){
	    if(isset($_COOKIE["Username"])){
			$username = $_COOKIE["Username"];
		}
		
		if(isset($_COOKIE["Username"]) && isset($_COOKIE["Password"])){
		    $this->storeCookies();
		}
		
		$ret = "<h1>Laborationskod as223jx</h1><h2>".$username." är inloggad</h2><br>
		<form action='' method='post'>
		<input type='submit' value='Logga ut' name='logOut'/>
		</form>";
		return $ret;
	}

    public function loginSuccess(){
        if($this->checkCookies()){
            return "";
        }
        return "Inloggning lyckades";
    }
    
	public function userPressedLogin(){
        if(isset($_POST["username"])){
    		return true;
        }
        else{
            return false;
        }
	}
	
	public function rememberMe(){
	    if (isset($_POST["remember"])){
	        $this->msg = "<p>Du har loggat in och vi kommer ihåg dig!</p>";
	        return true;
	    }
	    else{
	        return false;
	    }
		
			    		// if ($this->model->login($_POST["username"], $_POST["password"], $this->rememberValue)){
// 	    
	    			// if($this->rememberValue == true){
	    				// $this->setCookie();
// 	
	    				// header('Location: ' . $_SERVER['PHP_SELF']);
	}
	
	public function logOut(){
		if(isset($_POST["logOut"])){
		    $this->msg = "<p>Du har loggat ut!</p>";
			return true;
		}
		else{
			return false;
		}
	}
	
	public function checkCookies(){
		if(isset($_COOKIE["Username"]) && (isset($_COOKIE["Password"]))){
			$password = "";
			$username = "";
			
			$linesArr = array();
			chmod("src/storage.txt", 0777);
			$fh = fopen("src/storage.txt", "r");
			
			while (!feof($fh)){
				$line = fgets($fh);
				$line = trim($line);
				
				$linesArr[] = $line;
			}
			fclose($fh);
			
			if(count($linesArr) == 2){
    			$username = $linesArr[0];
    			$password = $linesArr[1];
			}
			
			$expire = fopen("src/cookieExpire.txt", "r");
			while (!feof($expire)){
				$line = fgets($expire);
				$line = trim($line);
				
				$expireArr[] = $line;
			}
			fclose($expire);
			
			if($_COOKIE["Username"] == $username && $_COOKIE["Password"] == $password && $this->model->loggedInStatus() == false && $expire > time()){
				echo "Inloggning lyckades via cookies";
				return true;
			}
			else if($_COOKIE["Username"] == $username && $_COOKIE["Password"] == $password && $this->model->loggedInStatus() == true && $expire > time()){
			    return true;
			}
			else{
				$this->model->resetCookies();
				echo "Felaktig information i cookie";
				return false;
			}
		}
		
		else{
			return false;
		}
	}
	
	public function storeCookies(){
        $linesArr = array();
		chmod("src/storage.txt", 0777);
		$fh = fopen("src/storage.txt", "r");
		
		while (!feof($fh)){
			$line = fgets($fh);
			$line = trim($line);
			
			$linesArr[] = $line;
		}
		fclose($fh);

        if(count($linesArr) != 2){
		    chmod("src/storage.txt", 0777);
			$storage = fopen("src/storage.txt", "w");
			$data = $_COOKIE["Username"] . "\n". $_COOKIE["Password"];
			fwrite($storage, $data);
			fclose($storage);
        }
	}
}
