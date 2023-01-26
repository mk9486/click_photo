<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../includes/config.php');
require_once('../includes/sql_builder/idiorm.php');
require_once('../includes/db.php');

session_start();

if(isset($_POST['action'])){
    if($_POST['action'] == "reservationConfirm"){
        reservationConfirm();
    }
} 

function reservationConfirm(){
    global $config;

    $name           = $_POST['name'];
    $email          = $_POST['email'];
    $message        = $_POST['message'];
    $contact_number = $_POST['contact'];
    $date           = $_POST['date'];

    $datenow = date("Y-m-d H:i:s");
    $code = 'CP';
    $ymd = date('ymd');
    $squence = 0;
    $booking_count = ORM::for_table('reservations')->count();
    
    if($booking_count == 0){
        $squence =+ 1;

    }else{
        $squence = $booking_count + 1;
    }

    $squence = str_pad($squence,4,0,STR_PAD_LEFT);

    $save_data = ORM::for_table('reservations')->create();

    $order_number = $code.$ymd.'_'.$squence;
    $save_data->number = $order_number;
    $save_data->res_name  = $name;
    $save_data->res_email = $email;
    $save_data->res_contact_number = $contact_number;
    $save_data->res_message = $message;
    $save_data->res_date = $date;
    $save_data->status = "Pending";
    $save_data->created_at = date('Y-m-d H:i:s');
    $save_data->is_delete = false;
    $save_data->save();

    $result["success"] = true;
    

    die(json_encode($result));
}

?>