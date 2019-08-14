<?php

use \Core\Database;
use \Core\Models\User;
use \Firebase\JWT\JWT;

require '../../bootstrap/init.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = Database::getInstance()->getConnection();
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// verify email
$user->email = $data->email;
$email_exists = $user->emailExists();

// verify password
if ($email_exists && password_verify($data->password, $user->password)) {
    $token = array(
        "iss" => JWT["iss"],
        "aud" => JWT["aud"],
        "iat" => JWT["iat"],
        "nbf" => JWT["nbf"],
        "data" => array(
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        )
    );
    http_response_code(200);
    // generate jwt
    $jwt = JWT::encode($token, JWT["key"]);
    echo json_encode(array(
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );
} else {
    // login failed
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}
