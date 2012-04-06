<?php

class Dependency {

	private $label;
	private $packages;
	private $require;

	public function __construct($label, $packages, $require=false){
		$this->label = $label;

		if(is_array($packages)){
			$this->packages = $packages;
		}
		else{
			$this->packages = array($packages);
		}

		$this->require = $require;
	}

	public function test(){
		if(! $this->require){
			foreach($this->packages as $package){
				if(! extension_loaded($package)){
					echo "Error: {$this->label}($package) NOT loaded\n";
					return FALSE;
				}
				else{
					echo "Success: {$this->label}($package) loaded\n";
					return TRUE;
				}
			}
		}
		else{
			$paths = explode(':', get_include_path());
			foreach($this->packages as $package){
				foreach($paths as $path){
					$p = implode('/', array($path, $package));
					if(! file_exists($p)){
						echo "Error: {$this->label} ($package) NOT loaded/in include path\n";
						return FALSE;
					}
					else{
						echo "Success: {$this->label} ($package) loaded/in include path\n";
						return TRUE;
					}
				}
			}
		}
	}
}

/* End of file Dependency.class.php */
