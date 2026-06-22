<?php
// Заголовки для CORS и указания, что возвращаем JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Параметры подключения к БД
$host = 'localhost'; // или ваш хост
$dbname = 'null';
$username = 'null';
$password = 'null';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT l.id as fid, l.lot_number, l.price, l.desc, ls.status_code, ls.status_name FROM t_lot l inner join t_lot_status ls on l.id_status = ls.id");
    $stmt->execute();

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[$row['fid']] = [
            'price' => number_format($row['price'], 0, '', ' '),
            'lot_number' => $row['lot_number'],
            'desc' => $row['desc'],
            'status_code' => $row['status_code'],
            'status_name' => $row['status_name']
        ];
    }

    echo json_encode($result);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
