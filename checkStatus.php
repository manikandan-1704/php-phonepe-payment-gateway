<?php

$merchantId = "PGTESTPAYUAT86";
$merchantTransactionId = "MT7850590068188104";
$saltKey = "96434309-7796-489d-8924-ab56988a6076";
$saltIndex = 1;

$url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{$merchantId}/{$merchantTransactionId}";

$headers = array(
    "Content-Type: application/json",
    "X-VERIFY: " . hash('sha256', "/pg/v1/status/{$merchantId}/{$merchantTransactionId}" . $saltKey) . "###" . $saltIndex,
    "X-MERCHANT-ID: " . $merchantId
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo "Response from PhonePe API:<br>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

curl_close($ch);

?>
