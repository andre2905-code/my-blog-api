<?php
require_once 'models/User.php';
class UserController
{
	private $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function index()
	{
		$users = $this->userModel->getAll();
		echo json_encode($users);
	}

	public function show($id)
	{
		$user = $this->userModel->getById($id);
		if ($user) {
			echo json_encode($user);
		} else {
			http_response_code(404);
			echo json_encode(['code' => 404, 'error' => 'User not found']);
		}
	}

	public function store()
	{
		$input = json_decode(file_get_contents('php://input'), true);

		if (!$this->validateUser($input)) {
			http_response_code(400);
			echo json_encode(['code' => 400, 'error' => 'Invalid input']);
			return;
		}

		$input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);

		$id = $this->userModel->create($input);
		http_response_code(201);
		echo json_encode(['code' => 201, 'id' => $id, 'message' => 'User created successfully!']);
	}

	public function update($id) {
		$input = json_decode(file_get_contents('php://input'), true);

		if ($this->userModel->update($id, $input)) {
			echo json_encode(['message' => "User $id updated"]);
		} else {
			http_response_code(404);
			echo json_encode(['error' => "Can't find any user with the ID given!"]);
		}
	}

	public function delete($id) {
		if ($this->userModel->delete($id)) {
			echo json_encode(['message' => 'User deleted']);
		} else {
			http_response_code(404);
			echo json_encode(['error' => 'User not found']);
		}
	}

	private function validateUser($data)
	{
		return isset($data['name']) && isset($data['email']) && isset($data['password']) && strlen($data['password']) >= 6;
	}
}