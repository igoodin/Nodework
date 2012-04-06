<?php
/**
 * This file houses the MpmInitialSchema class.
 *
 * This file may be deleted if you do not wish to use the build command or build on init features.
 *
 * @package    mysql_php_migrations
 * @subpackage Classes
 * @license    http://www.opensource.org/licenses/bsd-license.php  The New BSD License
 * @link       http://code.google.com/p/mysql-php-migrations/
 */

/**
 * The MpmInitialSchema class is used to build an initial database structure.
 *
 * @package    mysql_php_migrations
 * @subpackage Classes
 */
class MpmInitialSchema extends MpmSchema
{

	public function __construct()
	{
		parent::__construct();

		/* If you build your initial schema having already executed a number of migrations,
		* you should set the initial migration timestamp.
		*
		* The initial migration timestamp will be set to active and this migration and all
		* previous will be ignored when the build command is used.
		*
		* EX:
		*
		* $this->initialMigrationTimestamp = '2009-08-01 15:23:44';
		*/
		$this->initialMigrationTimestamp = null;
	}

	public function build()
 	{
		/* Add the queries needed to build the initial structure of your database.
		*
		* EX:
		*
		* $this->dbObj->exec('CREATE TABLE `testing` ( `id` INT(11) AUTO_INCREMENT NOT NULL, `vals` INT(11) NOT NULL, PRIMARY KEY ( `id` ))');
		*/
		$this->dbObj->exec('CREATE TABLE `applications` (
			  `application_id` int(11) NOT NULL AUTO_INCREMENT,
			  `application_name` varchar(255) DEFAULT NULL,
			  `application_domain` varchar(255) DEFAULT NULL,
			  `application_align` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`application_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;');

		$this->dbObj->exec('CREATE TABLE `users` (
			  `user_id` int(11) NOT NULL AUTO_INCREMENT,
			  `username` varchar(255) DEFAULT NULL,
			  `firstname` varchar(255) DEFAULT NULL,
			  `lastname` varchar(255) DEFAULT NULL,
			  `email` varchar(255) DEFAULT NULL,
			  `password` varchar(255) DEFAULT NULL,
			  `salt` varchar(255) DEFAULT NULL,
			  `ldap_domain` varchar(255) DEFAULT NULL,
			  `user_type` varchar(11) DEFAULT NULL,
			  PRIMARY KEY (`user_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;');

		$this->dbObj->exec('CREATE TABLE `application_ref_user` (
			  `application_id` int(11) DEFAULT NULL,
			  `user_id` int(11) DEFAULT NULL,
			  `permission` varchar(2) DEFAULT NULL,
			  KEY `application_id` (`application_id`),
			  KEY `user_id` (`user_id`),
			  CONSTRAINT `application_ref_user_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			  CONSTRAINT `application_ref_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');

		$this->dbObj->exec('CREATE TABLE `settings` (
			  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
			  `setting_key` varchar(255) DEFAULT NULL,
			  `setting_name` varchar(255) DEFAULT NULL,
			  `setting_description` text,
			  `setting_value` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`setting_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;');

		$this->dbObj->exec("INSERT INTO settings VALUES ('1', 'server_loc', 'Server Location', 'The location of the server that the javascript connects to', 'http://localhost:8124');");
		$this->dbObj->exec("INSERT INTO settings VALUES ('2', 'mongo_loc', 'MongoDB location', 'The location of the mongodb server', 'localhost');");
		$this->dbObj->exec("INSERT INTO settings VALUES ('3', 'mongo_db', 'MongoDB Database name', 'The name of the MongoDB database', 'db');
			");
		$this->dbObj->exec("INSERT INTO settings VALUES ('4', 'mongo_port', 'MongoDB Database Port', 'The MongoDB database port', '27017');
			");

		$this->dbObj->exec('CREATE TABLE `heatmap_keys` (
			  `key` varchar(255) DEFAULT NULL,
			  `expires` int(11) DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1');
	}

}

?>
