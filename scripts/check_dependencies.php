<?php

require_once('classes/Dependency.class.php');
require_once('classes/Package.class.php');

$packages = array(
	new Package('Apache2', 'apache2'),
	new Package('PHP', array('php5', 'php5-cli')),
	new Package('mod-php5', 'libapache2-mod-php5'),
	new Package('MySql', array('mysql-server', 'mysql-client')),
	new Package('node.js'),
	new Package('MognoDB')
);

$dependencies = array(
	new Dependency('Hashlib', 'hash'),
	new Dependency('JSON', 'json'),
	new Dependency('MySQL', array('mysql', 'mysqli')),
	new Dependency('MongoDB', 'Mongo'),
	new Dependency('LDAP', 'ldap')
);

echo "Required Programs\n";
echo "======================\n";
foreach($packages as $package){
	$package->run();
}

echo "\nRequired PHP extensions\n";
echo "=============================\n";
foreach($dependencies as $dependency){
	$dependency->test();
}

echo "\nChecking Python\n";
echo "===================\n";
exec('python pythonTests.py', $output);
foreach($output as $line){
	echo "$line\n";
}

echo "\nChecking Location of GeoLiteCity.dat\n";
echo "===================\n";
if(file_exists('../java/GeoLiteCity.dat')){
	echo "Success: GeoLiteCity.dat found\n";
}
else{
	echo "Error: GeoLiteCity.dat NOT found\n";
}

echo "\nAll Tests Compelete.\n";

/* End of file dependencies.php */
