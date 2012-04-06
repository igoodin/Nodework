<?php

class Migration_2011_02_22_19_05_42 extends MpmMysqliMigration
{

	public function up(ExceptionalMysqli &$mysqli)
	{
		$mysqli->exec('
			CREATE TABLE IF NOT EXISTS  `ci_sessions` (
			session_id varchar(40) DEFAULT \'0\' NOT NULL,
			ip_address varchar(16) DEFAULT \'0\' NOT NULL,
			user_agent varchar(50) NOT NULL,
			last_activity int(10) unsigned DEFAULT 0 NOT NULL,
			user_data text DEFAULT \'\' NOT NULL,
			PRIMARY KEY (session_id)
			);
		');
	}

	public function down(ExceptionalMysqli &$mysqli)
	{
		$mysqli->exec('DROP TABLE IF EXISTS `ci_sessions`;');
	}

}

?>
