<?php
//cache class
class ccc_cache {
	var $cacheDir = "./cache/"; //default cache directory
	var $cacheTime = 300; // cache seconds minutes*60
	var $cacheActive = true; // cache control 
	var $caching = false;
	var $cacheFile;
	var $cacheFileName;
	var $cacheLoggedInKey = "wordpress_logged_in"; //wordpress logged in cookie key 
	var $cacheLoggedIn = false;
	
	function __construct(){
		$this->cacheFile = md5($_SERVER['REQUEST_URI']).'.'.$_SERVER['SERVER_NAME'];
		$this->cacheDir.=$this->cacheFile;
		$this->cacheFileName = $this->cacheDir;
		if (count($_COOKIE)>0 && !empty($_COOKIE)) {
			foreach ($_COOKIE as $key => $val) {
				if (preg_match("/".$this->cacheLoggedInKey."/i", $key)) {
					$this->cacheLoggedIn = true;
				}
			}
		} else {
			$this->cacheLoggedIn = false;
		}
	}
	
	function start(){
			if(file_exists($this->cacheFileName) && (time() - filemtime($this->cacheFileName)) < $this->cacheTime && !$this->cacheLoggedIn && $this->cacheActive){
				$this->caching = false;
				echo file_get_contents($this->cacheFileName);
				exit();
			}else{
				$this->caching = true;
				@ob_start();
			}
	}
	
	function end(){
		if($this->caching && !$this->cacheLoggedIn && $this->cacheActive){
			file_put_contents($this->cacheFileName,ob_get_contents().'<!-- Cache Time: '.date("d.m.Y H:i:s").'-->');
			@ob_end_flush();
		}
	}

}
