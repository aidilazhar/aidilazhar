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

if(!empty($data->id) && !empty($data->gene) && !empty($data->drug) && !empty($data->response)) {
    $query = "UPDATE pharmacogenomics SET gene = :gene, drug = :drug, response = :response WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $data->id);
    $stmt->bindParam(":gene", $data->gene);
    $stmt->bindParam(":drug", $data->drug);
    $stmt->bindParam(":response", $data->response);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Record was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update record."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update record. Data is incomplete."));
}
