<?php

function deliver_response($response)
{
    // Define HTTP responses
    $http_response_code = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    // Set HTTP Response
    header('HTTP/1.1 ' . $response['status'] . ' ' . $http_response_code[$response['status']]);
    // Set HTTP Response Content Type
    header('Content-Type: application/json; charset=utf-8');
    // Format data into a JSON response
    $json_response = json_encode($response['data']);
    // Deliver formatted data
    echo $json_response;

    exit;
}

$url_array = explode('/', $_SERVER['REQUEST_URI']);
array_shift($url_array); //удалить первое значение, пустое
array_shift($url_array); // Удалить 2-е, "api"

// получаем действие
$action = $url_array[0];
// Получаем метод
$method = $_SERVER['REQUEST_METHOD'];

//данные по умолчанию
$response['status'] = 404;
$response['data'] = $action;

//подключить файл класса Products
require_once("products.php");

if (strcasecmp($action, 'products') == 0) {

    $product = new Product();

    switch ($method) {
        case 'GET':
            //если ID не указан, то возвращаем все товары
            if (!isset($url_array[1])) {
                $data = $product->getAllProducts();
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $idProducts = $url_array[1];
                $data = $product->getProducts($idProducts);
                if (empty($data)) {
                    $response['status'] = 404;
                    $response['data'] = array('error' => 'product not found');
                } else {
                    $response['status'] = 200;
                    $response['data'] = $data;
                }
            }
            break;
        case'POST':
            $json = file_get_contents('php://input');
            $post = json_decode($json); // decode to object

            // проверить полноту ввода
            if ($post->nameProducts == "" || $post->category == "" || $post->PurchasePrice == "" || $post->sellingPrice == "") {
                $response['status'] = 400;
                $response['data'] = array('error' => 'Not all data is filled');
            } else {
                $status = $product->insertProducts($post->nameProducts, $post->category, $post->PurchasePrice, $post->sellingPrice);
                if ($status > 0) {
                    $response['status'] = 201;
                    $response['data'] = array('success' => 'Data successfully saved');
                } else {
                    $response['status'] = 400;
                    $response['data'] = array('error' => 'save error');
                }
            }
            break;
    }
}

// Возвращаем ответ
deliver_response($response);

?>