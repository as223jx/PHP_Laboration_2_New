<?php

require_once("/src/LoginModel.php");
require_once("/src/LoginView.php");

class LoginController {
	private $view;
	private $model;
	private $username = "";
	private $password = "";
	private $value;
	
	public function __construct(){
		$this->model = new LoginModel();
		$this->view = new LoginView($this->model);
	}
	
	public function doControll(){
		
		// Om användaren vill logga in
		if($this->view->userPressedLogin()){
			
			// Hämtar inputvärdena från formuläret	
			$this->username = $this->view->getUsername();
			$this->password = $this->view->getPassword();
			$this->value = $this->view->rememberMe();
			
			// Försöker logga in med de angivna värdena
			if($this->model->login($this->username, $this->password, $this->value)){
				// Sätter cookies om användaren valt att bli ihågkommen
				if($this->value == true){
					$this->view->setCookie();
				}
				return $this->view->showLoggedIn($this->username);
			}	
			
			// Om uppgifterna vad fel visas login-forumläret igen
			else{
				return $this->view->showLoginForm();
			}
		
		}
		
		// Om användaren vill logga ut
		if($this->view->logOut()){
		    $this->model->logOut();
			return $this->view->showLoginForm();
		}
		
		// Om användaren är inloggad redan		
		if($this->model->userLoggedInStatus()){
			$this->username = $this->model->getLoggedInUser();
			return $this->view->showLoggedIn($this->username);
		}
		
		// Visar annars login-formuläret som default
		return $this->view->showLoginForm();
	}
			
//		$username = $this->view->getLoggedInUser();

		
        // if($this->model->loggedInStatus()){
        	// return $this->view->showLoggedIn($username);
        // }
// 		
        // else{
//         	
    		// if($this->view->checkCookies()){
    			// $this->model->setLoggedInStatus();
    			// echo $this->view->loginSuccess();
    			// return $this->view->showLoggedIn($username);
    		// }
// 			
    		// else{
//     			
    			// if($this->view->userPressedLogin()){
    			    // $this->model->setLoggedInStatus();
    			    // $username = $this->view->getLoggedInUser();
    			    // echo $this->view->loginSuccess();
    			 // //$this->view->storeCookies();
    				// return $this->view->showLoggedIn($username);
    			// }
//     			
    			// else{
    				// return $this->view->showLoginForm();
    			// }
    		// }
        // }
	//}
}