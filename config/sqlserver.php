<?php
//Connect MSSQL
$servername = 'IP';
$username = 'username';
$password = 'password';
$dbname = 'STD_PORTFOLIO';

try {
    $sqlconn = new PDO("sqlsrv:server=$servername ; database = $dbname", $username, $password);
    $sqlconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
