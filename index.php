<?php
// set response headers for API
header('Content-Type: application/json'); //Informa ao navegador que espera um JSON como resposta
header('Access-Control-Allow-Origin: *'); // Permite requisições de qualquer domínio
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Permite esses métodos HTTP
header('Access-Control-Allow-Headers: Content-Type'); // Permite header Content-Type nas requisições

// Extrai o método HTTP da requisição: GET, POST, PUT ou DELETE
$method = $_SERVER['REQUEST_METHOD'];

// Pega o caminho da URL em questão, ignorando os parâmetros (?param=value)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Separa o path da URL em segmentos pela '/': http://localhost/api/users/123 -> 'api', 'users', '123'
$segments = explode('/', trim($path, '/'));

// Remove o "api" do array $segments
if($segments[0] === 'api') {
	array_shift($segments);
}

// Define o nome do recurso solicitado, e o ID (opcional). Caso não haja recurso, o $resource é vazio. Caso não haja ID, o $id é null
$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;

try {
	switch($resource) {
		case 'users':
			require_once 'controllers/UserController.php';
			$controller = new UserController();
			handleRequest($controller, $method, $id);
			break;
		default:
			http_response_code(404);
			echo json_encode(['error' => "Resource not found"]);
	}
} catch (Exception $error) {
	http_response_code(500);
	echo json_encode(['error' => $error->getMessage()]);
}

function handleRequest ($controller, $method, $id) {
	switch ($method) {
		case 'GET':
			if ($id) {
				$controller->show($id);
			} else {
				$controller->index();
			}
			break;
		case 'POST':
			$controller->store();         
			break;
		case 'PUT':
			$controller->update($id);  
			break;
		case 'DELETE':
			$controller->delete($id);    
			break;
	}
}