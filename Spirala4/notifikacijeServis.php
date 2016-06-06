<?php
function zag() {
    header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
    header('Content-Type: text/html');
    header('Access-Control-Allow-Origin: *');
}


function rest_get($request, $data) { 

    $id = $data['id'];
    $id = intval($id);
    $connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

    $rez = $connection->query("

        SELECT k.vijest, COUNT(k.id) broj, COUNT(kd.id) broj2
        FROM komentarroditelj k LEFT JOIN komentardijete kd
        ON k.id = kd.roditelj
        WHERE k.vijest IN (SELECT v.id FROM vijesti v WHERE v.autor = $id)  
        GROUP BY k.vijest
    ");


    $vijestiAutora = array();
    foreach($rez->fetchAll(PDO::FETCH_ASSOC) as $vijestiID)
    {
        array_push($vijestiAutora, $vijestiID);
    }



    echo json_encode($vijestiAutora);
}


function rest_post($request, $data) { }
function rest_delete($request) { }
function rest_put($request, $data) { }
function rest_error($request) { }

$method  = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

switch($method) {
    case 'PUT':
    parse_str(file_get_contents('php://input'), $put_vars);
    zag(); $data = $put_vars; rest_put($request, $data); break;
    case 'POST':
    zag(); $data = $_POST; rest_post($request, $data); break;
    case 'GET':
    zag(); $data = $_GET; rest_get($request, $data); break;
    case 'DELETE':
    zag(); rest_delete($request); break;
    default:
    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    rest_error($request); break;
}
?>
