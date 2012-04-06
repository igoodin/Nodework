<?php

class Migration_2011_04_12_21_16_46 extends MpmMysqliMigration
{

	public function up(ExceptionalMysqli &$mysqli)
	{
		$mysli->exec('
			ALTER TABLE users
			DROP COLUMN username,
			DROP COLUMN ldap_domain
		');

		$mysli->exec('
			ALTER TABLE application_ref_user
			ADD COLUMN is_owner tinyint(1)
		');

		$mysqli->exec('
			CREATE TABLE IF NOT EXISTS `registration_keys` (
			  `user_id` int(11) DEFAULT NULL,
			  `key` varchar(255) DEFAULT NULL,
			  `expires` int(11) DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1
		');

		$mysqli->exec('
			CREATE TABLE IF NOT EXISTS `recovery_keys` (
			  `user_id` int(11) DEFAULT NULL,
			  `key` varchar(255) DEFAULT NULL,
			  `expires` int(11) DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1
		');
	}

	public function down(ExceptionalMysqli &$mysqli)
	{
		$mysqli->exec('DROP TABLE IF EXISTS `registration_keys`;');
		$mysqli->exec('DROP TABLE IF EXISTS `recovery_keys`;');
		$mysli->exec('
			ALTER TABLE users
			ADD COLUMN username,
			ADD COLUMN ldap_domain
		');

		$mysli->exec('
			ALTER TABLE application_ref_user
			DROP COLUMN is_owner
		');
	}

}

?>