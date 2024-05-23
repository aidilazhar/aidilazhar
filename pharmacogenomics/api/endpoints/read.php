<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, gene, drug, response FROM pharmacogenomics";
$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
if($num > 0) {
    $records_arr = array();
    $records_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $record_item = array(
            "id" => $id,
            "gene" => $gene,
            "drug" => $drug,
            "response" => $response
        );
        array_push($records_arr["records"], $record_item);
    }

    http_response_code(200);
    echo json_encode($records_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No records found."));
}
