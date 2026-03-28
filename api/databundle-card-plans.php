<?php
header('Content-Type: application/json');
include("../../func/bc-connect.php");
include("../../func/bc-func.php");

$api_key = mysqli_real_escape_string($connection_server, $_GET['api_key'] ?? '');
if(empty($api_key)) exit(json_encode(["status" => "failed", "desc" => "Missing API Key"]));

$vendor_id = resolveVendorID();
$user_q = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='$vendor_id' AND api_key='$api_key' AND api_status=1 AND status=1");
$user = mysqli_fetch_assoc($user_q);
if(!$user) exit(json_encode(["status" => "failed", "desc" => "Unauthorized"]));

$vid = $user["vendor_id"];
$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
$acc_level_table_name = $account_level_table_name_arrays[$user["account_level"]] ?? "sas_smart_parameter_values";

$bundle_plans_sql = "SELECT p.*, prod.product_name FROM sas_databundle_plans p JOIN sas_products prod ON p.product_id = prod.id WHERE p.vendor_id='$vid' && p.status=1";
$bundle_plans_query = mysqli_query($connection_server, $bundle_plans_sql);

$status_map = [];
$types = ["sme-data", "shared-data", "cg-data", "dd-data"];
$type_tables = ["sme-data" => "sas_sme_data_status", "shared-data" => "sas_shared_data_status", "cg-data" => "sas_cg_data_status", "dd-data" => "sas_dd_data_status"];
foreach($types as $t){
    $st_res = mysqli_query($connection_server, "SELECT product_name, api_id FROM ".$type_tables[$t]." WHERE vendor_id='$vid' AND status=1");
    while($st_row = mysqli_fetch_assoc($st_res)){
        $status_map[$t][$st_row['product_name']] = $st_row['api_id'];
    }
}

$pricing_map = [];
$pricing_res = mysqli_query($connection_server, "SELECT api_id, product_id, val_1, val_2 FROM $acc_level_table_name WHERE vendor_id='$vid' AND status = 1");
while($pr_row = mysqli_fetch_assoc($pricing_res)){
    $pricing_map[$pr_row['api_id']][$pr_row['product_id']][$pr_row['val_1']] = $pr_row['val_2'];
}

$response_data = [];

while($plan = mysqli_fetch_assoc($bundle_plans_query)){
    $dtype = $plan['data_type'];
    $pname = $plan['product_name'];
    $pid = $plan['product_id'];
    $pcode = $plan['plan_code'];

    $api_id = $status_map[$dtype][$pname] ?? null;
    $price = ($api_id && isset($pricing_map[$api_id][$pid][$pcode])) ? $pricing_map[$api_id][$pid][$pcode] : null;

    if($price !== null){
        $response_data[strtoupper($pname)][] = [
            "PLAN_CODE" => $pcode,
            "DATA_TYPE" => $dtype,
            "AMOUNT" => $price,
            "DURATION" => $plan['validity_days'] . " Days"
        ];
    }
}

echo json_encode(["status" => "success", "MOBILE_NETWORK" => $response_data]);
?>
