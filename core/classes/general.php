<?php 

class General {	

	public function logged_in() {
		return(isset($_SESSION['id'])) ? true : false;
	}

	public function logged_in_protect() {
		if ($this->logged_in() === true) {
			
			$user_id = $_SESSION['id'];
			header('Location: '.$_SESSION['username']);
			exit();		
		}
	}
	 
	public function logged_out_protect() {
		
		$domain = $_SERVER['SERVER_NAME'];

		if ($this->logged_in() === false) {
			if ($domain == "localhost") {
	            header('Location: /petstapost');
	            exit();
	        } else if ($domain == "petstapost.com") {
	            header('Location: /');
	            exit();
	        }
		}	
	}
	
	public function file_newpath($path, $filename){
		if ($pos = strrpos($filename, '.')) {
		   $name = substr($filename, 0, $pos);
		   $ext = substr($filename, $pos);
		} else {
		   $name = $filename;
		}
		
		$newpath = $path.'/'.$filename;
		$newname = $filename;
		$counter = 0;
		
		while (file_exists($newpath)) {
		   $newname = $name .'_'. $counter . $ext;
		   $newpath = $path.'/'.$newname;
		   $counter++;
		}
		
		return $newpath;
	}
}