<?php 
	class DbConnect {
		private $host = '18.221.215.201';
		private $dbName = 'fcm-push';
		private $user = 'root';
		private $pass = 'gc::cloud*a2z#cyberData@#AWS';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>