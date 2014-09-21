<?php

class HTMLView {
	
	public function echoHTML($body){
		if ($body == NULL) {
			throw new \Exception("Body can't be null");
		}
		
		echo "
			<!DOCTYPE html>
			<meta charset='UTF-8'>
			<html>
			<body>
				$body
			</body>
			</html>
		";
	}
}
