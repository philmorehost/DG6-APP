<?php session_start();
header("Content-Type: application/json");
include_once("../../func/bc-connect.php");
include_once("../../func/bc-func.php");

$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_REQUEST;
}

$api_key = mysqli_real_escape_string($connection_server, trim(strip_tags($input["api_key"] ?? '')));

if (empty($api_key)) {
    echo json_encode(["status" => "error", "message" => "API Key is required"]);
    exit;
}

$vendor_id = resolveVendorID();
$get_vendor = mysqli_fetch_array(mysqli_query($connection_server, "SELECT id FROM sas_vendors WHERE id='$vendor_id' AND status=1 LIMIT 1"));
if (!$get_vendor) {
    echo json_encode(["status" => "error", "message" => "Vendor not found"]);
    exit;
}

$check_user = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='$vendor_id' AND api_key='$api_key' LIMIT 1");
if (mysqli_num_rows($check_user) == 1) {
    $user = mysqli_fetch_assoc($check_user);
    $username = $user['username'];

    $amount = (float)($input["amount"] ?? 0);
    $gateway = mysqli_real_escape_string($connection_server, $input["gateway"] ?? 'Manual Bank Deposit');
    $reference = "MAN_" . time() . rand(100, 999);

    if ($amount < 1) {
        echo json_encode(["status" => "error", "message" => "Invalid amount"]);
        exit;
    }

    $q = "INSERT INTO sas_transactions (vendor_id, product_unique_id, type_alternative, reference, username, amount, discounted_amount, description, mode, status)
          VALUES ('$vendor_id', 'manual_funding', 'Wallet Funding', '$reference', '$username', '$amount', '$amount', 'Manual Funding Request: $gateway', 'APP', '2')";

    if (mysqli_query($connection_server, $q)) {
        echo json_encode([
            "status" => "success",
            "message" => "Funding request submitted successfully. Please wait for admin approval.",
            "reference" => $reference
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to submit request: " . mysqli_error($connection_server)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid API Key"]);
}
mysqli_close($connection_server);
