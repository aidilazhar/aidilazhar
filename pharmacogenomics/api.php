<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");

include 'db.php';

$response = array();
$drug_name = isset($_GET['drug_name']) ? $_GET['drug_name'] : '';

if (!empty($drug_name)) {
    $sql = "SELECT p.first_name, p.last_name, d.drug_name, dr.response, dr.dosage, dr.adverse_reactions
            FROM patients p
            JOIN drug_responses dr ON p.patient_id = dr.patient_id
            JOIN drugs d ON dr.drug_id = d.drug_id
            WHERE d.drug_name = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $drug_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['status'] = 'success';
        $response['data'] = array();

        while ($row = $result->fetch_assoc()) {
            $data = array(
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'drug_name' => $row['drug_name'],
                'response' => $row['response'],
                'dosage' => $row['dosage'],
                'adverse_reactions' => $row['adverse_reactions']
            );
            array_push($response['data'], $data);
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No patients found for the given drug response';
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid input';
}

$conn->close();
echo json_encode($response);
?>
