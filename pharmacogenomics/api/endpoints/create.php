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

if(!empty($data->patient_id) && !empty($data->drug_id) && !empty($data->response) && !empty($data->dosage)) {
    $query = "INSERT INTO drug_responses (patient_id, drug_id, response, dosage, adverse_reactions) VALUES (:patient_id, :drug_id, :response, :dosage, :adverse_reactions)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":patient_id", $data->patient_id);
    $stmt->bindParam(":drug_id", $data->drug_id);
    $stmt->bindParam(":response", $data->response);
    $stmt->bindParam(":dosage", $data->dosage);
    $stmt->bindParam(":adverse_reactions", $data->adverse_reactions);

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
