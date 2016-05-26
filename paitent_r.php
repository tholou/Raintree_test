<?php

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
echo mysqli_get_server_info($r);
echo "\n";

$r2 = mysqli_select_db($r,"raintree_test");
if (!$r2)
{
    trigger_error("Cannot select database", E_USER_ERROR);
} else {
    echo "Database Selected\n";
}


$query = "SELECT  p._id as id, p.pn as pn, p.first as first, p.last as last, p.dob, i.iname as iname, DATE_FORMAT(i.from_date,'%m-%d-%y') as from_date, DATE_FORMAT(i.to_date,'%m-%d-%y') as to_date From patient p, insurance i 
Where p._id = i.patient_id
ORDER BY i.from_date ASC, last";

$query2 = "SELECT CONCAT(first,last) as FullName FROM patient";
$rs = mysqli_query($r,$query);
$rs2 = mysqli_query($r,$query2);
//$row2 = mysqli_fetch_all($rs2);
/*$nameArray = [];
foreach ($row2 as $item) {

    //print_r($item);
    $nameArray[] = $item;
    print_r($nameArray);

}*/
/*foreach ($row2 as $str){
    //echo $str[13];
    $str = str_replace(' ', '', $str);
    $str = strtoupper($str);
    $arr = str_split($str);

    //echo $arr[0];

    $rep = array_count_values($arr);
    $arrlength = count($arr);
    foreach ($rep as $key => $value) {

        $per =($value / $arrlength) * 100;

        echo $key . "  =  " . $value . "  " . round($per, 2) . "%" ."\n";
    }
}*/
echo "\n";
echo "\n";

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
    print_r($nAA);
    echo "\n";
    echo $nAA[1];
    echo "\n";
    echo "\n";
    $namelength1 = count($nAA);
    echo  $namelength1;
    echo "\n";
    echo "\n";

    $strI = implode($nAA);
    $strL = strlen($strI);
    $strArray = count_chars(strtoupper($strI),1);
    foreach ($strArray as $key=>$value){
        //echo "The character ".chr($key)." was found $value time(s)";
        $per = $value / $strL * 100;
        echo chr($key) . "    " . $value . "   " . round($per, 2) . "%" ."\n";
    }

    /*foreach ($nAA as $str){
        //echo $str;
        for ($i=0; $i<strlen($str); $i++){

            //$strU = $str[$i];
            //print_r($strU);
            //$strL = strlen($strU);
            //echo $strL;
           // echo $strU;
            $strArray = count_chars(strtoupper($str[$i]),1);
            foreach ($strArray as $key=>$value){
                //echo "The character ".chr($key)." was found $value time(s)";
                //echo chr($key) . "  =  " . $value . "\n";
            }
        }

    }*/

}
echo "\n";
echo "\n";
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
mysqli_close($r);





