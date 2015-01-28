<?php
final class PF{
	
	private static $instance;
	private function __construct(){
		
	}
	
	public function getInstance(){
		if (!self::$instance){
			self::$instance = new PF();
		}
		return self::$instance;
	}
	
	public function run(){
		phpinfo();
	}
}