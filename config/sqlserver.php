<?php
//Connect MSSQL
$servername = '203.158.254.86';
$username = 'project_searchportfolio';
$password = 'P@rtf@li@Project';
$dbname = 'STD_PORTFOLIO';

try {
    $sqlconn = new PDO("sqlsrv:server=$servername ; database = $dbname", $username, $password);
    $sqlconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
