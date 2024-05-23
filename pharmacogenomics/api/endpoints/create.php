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

if(!empty($data->gene) && !empty($data->drug) && !empty($data->response)) {
    $query = "INSERT INTO pharmacogenomics (gene, drug, response) VALUES (:gene, :drug, :response)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":gene", $data->gene);
    $stmt->bindParam(":drug", $data->drug);
    $stmt->bindParam(":response", $data->response);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Record was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create record."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create record. Data is incomplete."));
}
