<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->response_id)) {
    $query = "DELETE FROM drug_responses WHERE response_id = :response_id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":response_id", $data->response_id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Record was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete record."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete record. Data is incomplete."));
}
