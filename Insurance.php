<?php

/**
 * Created by PhpStorm.
 * User: eyinade
 * Date: 5/3/2016
 * Time: 12:39 PM
 */

require_once('./includes/db.php');
include_once ('PatientRecordInterface.php');

class Insurance implements PatientRecordInterface
{

    protected $_id;
    protected $patient_id;
    protected $iname;
    protected $from_date;
    protected $to_date;
    protected $link;

    function __construct($insuranceId)
    {
        $this->link = new db();
        try {
            if (!$this->setProperties($insuranceId, $this->link)) {
                throw new Exception("Invalid insuranceID");
            }
        } catch (Exception $e) {
            echo 'Insurance Instatiation failed due to: ', $e->getMessage(), "\n";
        }
    }

    function getId()
    {
        return $this->_id;
    }

    function getPatientNumber()
    {
        $query = "SELECT pn FROM patient WHERE _id = $this->patient_id";
        $host = "localhost";
        $user = "root";
        $pass = "";

        $r = mysqli_connect($host, $user, $pass);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            echo "Connection established\n";
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            } else {
                echo "Connection established\n";
            }
            $r2 = mysqli_select_db($r, "raintree_test");
            if (!$r2) {
                trigger_error("Cannot select database", E_USER_ERROR);
            } else {
                echo "Database Selected\n";
            }
            $res = mysqli_query($r, $query);
            var_dump($res);
            $row = mysqli_fetch_assoc($res);
            return $row['pn'];
        }
        function checkPolicyValidity($dateStr)
        {
            $dateSubs = explode('-', $dateStr);
            $newString = $dateSubs[2] . '-' . $dateSubs[0] . '-' . $dateSubs[1];
            $ans = "False";
            //echo "is $dateStr (mm-dd-yy) between $this->from_date and $this->to_date";
            if (isset($this->to_date) && isset($this->from_date)) {
                if ((strtotime($newString) > strtotime($this->from_date)) && (strtotime($newString) < strtotime($this->to_date))) {
                    $ans = "True";
                } else {
                    $ans = "False";
                }
            } elseif (isset($this->from_date)) {
                if ((strtotime($newString) > strtotime($this->from_date))) {
                    $ans = "True";
                }
            }
            return $ans;
        }

        function setProperties($id, $link)
        {
            $insurancyPolicy = $link->exec("SELECT * FROM insurance WHERE _id = $id");
            if ($insurancyPolicy->num_rows < 1) {
                return False;
            } else {
                $policyRecord = $insurancyPolicy->fetch_assoc();
                $this->_id = $policyRecord['_id'];
                $this->patient_id = $policyRecord['patient_id'];
                $this->iname = $policyRecord['iname'];
                $this->from_date = $policyRecord['from_date'];
                $this->to_date = $policyRecord['to_date'];
                $this->link->disconnect();
                return True;
            }
        }
    }
}


//mysqli_close($r);


/*$in =  new Insurance(1);
# mm/dd/yy
echo $in->getPatientNumber();
echo $in->checkPolicyValidity('03-05-16')."\n";*/