<?php
require_once 'config/Database.php';

class User {
	private $db;

	public function __construct() {
		$this->db = Database::getInstance()->getConnection();
	}

	/**
	 * Retrieve all users from database
	 * @return array - Array of user records
	 */
	public function getAll() {
		$stmt = $this->db->query("SELECT * FROM users");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getById($id) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->execute([$id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($data) {
		$stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
		$stmt->execute([$data['name'], $data['email'], $data['password']]);

		return $this->db->lastInsertId();
	}

	/**
	 * Update existing user
	 * @param int $id - User ID to update
	 * @param array $data - New user data
	 * @return bool - True if update successful, false otherwise
	 */
	public function update($id, $data) {
		$stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
		return $stmt->execute([$data['name'], $data['email'], $id]);
	}

	public function delete($id) {
		$stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
		return $stmt->execute([$id]);
	}
}