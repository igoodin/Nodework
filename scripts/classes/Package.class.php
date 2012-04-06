<?php

class Package {
	private $label;
	private $packages;

	public function __construct($label, $packages=FALSE){
		$this->label = $label;

		if($packages !== FALSE){
			if(is_array($packages)){
				$this->packages = $packages;
			}
			else{
				$this->packages = array($packages);
			}
		}
		else{
			$this->packages = FALSE;
		}
	}

	public function run(){
		echo "Package: {$this->label}\n";
		if($this->packages !== FALSE){
			$p = implode(' ', $this->packages);
			echo "\tInstall With: sudo apt-get install $p\n";
		}
	}
}

/* End of file Package.class.php */
