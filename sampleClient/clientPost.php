<?php
// URL API

$url = 'http://rest-api/api/products';

$client = curl_init();

$data = array("nameProducts" => "", "category" =>"", "PurchasePrice" => "", "sellingPrice" => "");
$data = json_encode($data);

$options = array(
    CURLOPT_URL				=> $url, // Установить URL API
    CURLOPT_CUSTOMREQUEST 	=> "GET", // Установить метод запроса
    CURLOPT_RETURNTRANSFER	=> true, // true, чтобы вернуть перевод в виде строки
    CURLOPT_POSTFIELDS 		=> $data, // Отправить данные в HTTP POST
);

curl_setopt_array( $client, $options );

// Выполнить и получить ответ
$response = curl_exec($client);
// Получить ответ HTTP Code
$httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
// закрыть cURL session
curl_close($client);

echo '<h3>HTTP Code</h3>';
echo $httpCode;
echo '<h3>Response</h3>';
echo $response;

?>