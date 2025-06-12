<?php

class Database {
	private static $instance = null;
	private $connection;

	private function __construct() {
		$host = 'localhost';
		$dbname = 'my_blog';
		$username = 'andre';
		$password = '@sR290905';

		try {
			$this->connection = new PDO(
				"mysql:host=$host;dbname=$dbname",
				$username,
				$password,
				[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
			);
		} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	}

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new Database();
		}

		return self::$instance;
	}

	public function getConnection() {
		return $this->connection;
	}
}