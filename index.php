<?php

echo "<center><h2>Phonepe Payment Gateway Integration</h2></center>";

//This credentials used for testing purposes, please use your own credentials.

$apiKey = '96434309-7796-489d-8924-ab56988a6076';
$merchantId = 'PGTESTPAYUAT86';
$keyIndex = 1;

// Prepare the payment request data (you should customize this)
$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "TX123456789",
    "merchantUserId"=>"M123456789",
    'amount' => 1000, // Amount in paisa (10 INR)
    'redirectUrl'=>"http://localhost:8080/Phonepe/paymentSuccess.php",
    'redirectMode'=>"POST",
    'callbackUrl'=>"http://localhost:8080/Phonepe/paymentSuccess.php",
    "merchantOrderId"=> "12345",
   "mobileNumber"=>"9876543210",
   "message"=>"payment of 10 INR",
   "email"=>"test@gmail.com",
   "shortName"=>"Test",
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);

 
$jsonencode = json_encode($paymentData);
$payloadMain = base64_encode($jsonencode);
 
$payload = $payloadMain . "/pg/v1/pay" . $apiKey;
$sha256 = hash("sha256", $payload);
$final_x_header = $sha256 . '###' . $keyIndex;
$request = json_encode(array('request'=>$payloadMain));

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);
 
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;

echo "<a href='".$payUrl."'>Pay Now</a>";
 
}
}

?>