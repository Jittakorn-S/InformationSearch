
<?php
include 'sqlserver.php';
$sql = "select *
from (SELECT std.FACULTYID as id , std.FACULTYNAME as name , std.PROGRAMNAME, std.FACULTYID as parent_id ,std.DEPARTMENTID
 FROM [AVSREGDW].[dbo].[STUDENTINFO] std
 where std.FACULTYID != 0 and std.DEPARTMENTID >0 group by std.FACULTYID , std.FACULTYNAME , std.PROGRAMNAME, std.DEPARTMENTID ) a
 group by a.id , a.name , a.PROGRAMNAME , a.parent_id , a.DEPARTMENTID order by a.id asc";

$getresults = $sqlconn->prepare($sql);
$getresults->execute();

$searchdata = array();
while ($results = $getresults->fetch(PDO::FETCH_ASSOC)) {
    array_push($searchdata, $results);
}
header('Content-Type: application/json');
echo json_encode($searchdata);
