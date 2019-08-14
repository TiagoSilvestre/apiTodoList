<?php
use \Core\Database;
use \Core\Models\Todolist;
use \Firebase\JWT\JWT;

require '../../bootstrap/init.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
$db = Database::getInstance()->getConnection();
$todolist = new Todolist($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
$jwt = isset($data->jwt) ? $data->jwt : "";
 
if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, JWT["key"], array('HS256'));
        
        $todolist->user_id = $decoded->data->id;
        
        if($todolist->getList()) {
            http_response_code(200);
            echo json_encode($todolist->list_content);
        } else {
            echo json_encode(false);
        } 
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
} else {    
    // show error message if jwt is empty
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}