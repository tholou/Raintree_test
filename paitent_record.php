<?php

/*1. Database Connection*/
$host = "localhost";
$user = "root";
$pass = "";

$r = mysqli_connect($host, $user, $pass);

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Connection established\n";
}
$r2 = mysqli_select_db($r,"raintree_test");
if (!$r2)
{
    trigger_error("Cannot select database", E_USER_ERROR);
} else {
    echo "Database Selected\n";
}

echo "\n";
echo "\n";


/* My Query to handle Question 3.2(a)*/
$query = "SELECT  p._id as id, p.pn as pn, p.first as first, p.last as last, p.dob, i.iname as iname, DATE_FORMAT(i.from_date,'%m-%d-%y') as from_date, DATE_FORMAT(i.to_date,'%m-%d-%y') as to_date From patient p, insurance i 
Where p._id = i.patient_id
ORDER BY i.from_date ASC, last";

$rs = mysqli_query($r,$query);
while ($row = mysqli_fetch_assoc($rs)) {
    printf("%s, ", $row["pn"]);
    printf("%s, ", $row["last"]);
    printf("%s, ", $row["first"]);
    printf("%s, ", $row["iname"]);
    printf("%s, ", $row["from_date"]);
    printf("%s\n ", $row["to_date"]);

    printf("\n\n");
}
mysqli_free_result($rs);

echo "\n";
echo "\n";


/* My Query to handle Question 3.2(b)*/
$query2 = "SELECT CONCAT(first,last) as FullName FROM patient";

$rs2 = mysqli_query($r,$query2);

$nameArray = [];

while ($row2 = mysqli_fetch_all($rs2)) {
    $nameArray[] = $row2;
    function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    $nAA = array_flatten($nameArray);
    $strI = implode($nAA);
    //echo $strI;
    $strL = strlen($strI)-1;
    //echo $strL;
    $strArray = count_chars(strtoupper($strI),1);
    foreach ($strArray as $key=>$value){
        $per = ($value / $strL) * 100;
        echo chr($key) . "  " . $value . "  " . round($per, 2) . "%" ."\n";
    }
}
mysqli_free_result($rs2);
mysqli_close($r);





