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

    if ($user['account_level'] >= 3 && !validateAPIDomain($user)) {
        echo json_encode(["status" => "error", "message" => "Unauthorized Domain. Please whitelist your domain in API Settings."]);
        exit;
    }

    $user_id = $user['id'];
    $username = $user['username'];

    // Ensure PayHub accounts are synced/generated
    syncPayhubVirtualAccounts($vendor_id, $user['email'], false);

    $banks = [];
    $q = mysqli_query($connection_server, "SELECT bank_name, account_name, account_number FROM sas_user_banks WHERE username='$username' AND vendor_id='$vendor_id'");
    while($r = mysqli_fetch_assoc($q)) {
        $banks[] = $r;
    }

    echo json_encode([
        "status" => "success",
        "data" => $banks
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid API Key"]);
}
mysqli_close($connection_server);
