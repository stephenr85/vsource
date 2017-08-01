<?php

namespace Vsource;

class Backup {

	private $rootBackupPath = VSOURCE_DIR.'/private/backups/';
	private $alias;

	public function getApp(){
		return \Vsource\App::getInstance();
	}

	public function getAlias(){
		if(!$this->alias){
			$this->alias = date('Y-m-d_hiA');
		}
		return $this->alias;
	}

	public function setAlias($alias){
		$this->alias = $alias;
		return $this;
	}

	public function getBackupPath(){
		return $this->rootBackupPath.'/'.$this->getAlias();
	}

	#Function to backup database to a zip file
	function backupDatabase() 
	{
		$host = VSOURCE_DB_HOST;
		$db = VSOURCE_DB_NAME;
		$username = VSOURCE_DB_USER;
		$password = VSOURCE_DB_PASSWORD;
		$sqlPath = $this->getBackupPath().'/'.$db.'.sql';

		if(!file_exists($this->getBackupPath())){
			mkdir($this->getBackupPath());
		}
		#Execute the command to create backup sql file
		passthru("mysqldump --host={$host} --user={$username} --password={$password} --quick --add-drop-table --add-locks --extended-insert --lock-tables --all {$db} > " . $sqlPath);

		#Now zip that file
		$zip = new \ZipArchive();
		$zipPath = $sqlPath.'.zip';
		if ($zip->open($zipPath, \ZIPARCHIVE::CREATE) !== TRUE) {
			exit("cannot open <$zipPath>n");
		}
		$zip->addFile($sqlPath, basename($sqlPath));
		$zip->close();
		#Now delete the .sql file without any warning
		//@unlink($sqlPath);
		#Return the path to the zip backup file
		return $zipPath;
	} 

}