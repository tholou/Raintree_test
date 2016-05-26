<?php

/**
 * Created by PhpStorm.
 * User: eyinade
 * Date: 5/3/2016
 * Time: 12:21 PM
 */

require_once('./includes/db.php');
require_once('PatientRecordInterface.php');
class Patient implements PatientRecordInterface
{
    protected $_id;
    protected $pn;
    protected $first;
    protected $last;
    protected $dob;
    protected $insurancePolicies=[];
    function __construct($pn){
        $this->pn = $pn;
        $db = new db();
        $this->setProperties($pn,$db);
    }
    function getId(){
        return $this->_id;
    }
    function getPatientNumber(){
        return $this->pn;
    }
    function patientFullName(){
        return $this->first." ".$this->last;
    }
    function patientInsurancePolicies(){
        return $this->insurancePolicies;
    }
    function checkInsuranceValidity($date){
        $info ="Patient Number, First Last, Insurance name, Is Valid \n";
        $dateSubs = explode('-',$date);
        $newString = $dateSubs[2].'-'.$dateSubs[0].'-'.$dateSubs[1];
        foreach ($this->insurancePolicies as $policy){
            if(strtotime($policy['from_date']) <= strtotime($newString) && strtotime($policy['to_date']) >= strtotime($newString) ){
                $info .= "$this->pn, $this->first $this->last, $policy[iname], Yes \n";
            }else{
                $info .=  "$this->pn, $this->first $this->last, $policy[iname], No \n";
            }
        }
        return $info;
    }
    function setProperties($pn,$link){
        $res = $link->exec("SELECT * FROM patient WHERE pn = $pn");
        var_dump($res);
        /*if (!$res) {
            throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
        }*/
        $patientRec = $res->fetch_assoc();
        $this->_id = $patientRec['_id'];
        $this->first = $patientRec['first'];
        $this->last = $patientRec['last'];
        $this->dob = $patientRec['dob'];
        $insurePolicies = $link->exec("SELECT iname, from_date, to_date FROM insurance WHERE patient_id = $this->_id");
        while ($row=mysqli_fetch_assoc($insurePolicies)){
            $this->insurancePolicies[] = $row;
        }
        $link->disconnect();
        return;
    }


}

/*$testPatient =  new Patient('A0000100');
var_dump($testPatient);
echo $testPatient->checkInsuranceValidity('02-19-16');*/