<?php 
  require_once __DIR__ .'/config.php';
  require_once __DIR__ .'/server.php';
  require_once __DIR__ .'/account.php';

  class CUSTOMER {
    public function __construct(){
      $this->db = new Connect;
    }

    function Select($email=NULL,$all=0){
      $customers = array();
      if(isset($email) && $all == 0){
        $data = $this->db->prepare('SELECT * FROM customer WHERE `email`= ?');
        $data->bindParam(1,$email);
      }
      else if($all == 1 && !isset($email)){
        $data = $this->db->prepare('SELECT * FROM customer ORDER BY id');
      }
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $customers[$OutputData['id']] = array(
          'id'     => $OutputData['id'],
          'name'   => $OutputData['name'],
          'email'  => $OutputData['email'],
          'gender' => $OutputData['gender'],
          'dob'    => $OutputData['dob']
        );
      }
      return $customers;
    }

    function Insert($name,$gender,$dob,$email){
      $customer = array();
      $data = $this->db->prepare('INSERT INTO customer(`name`,`gender`,`dob`,`email`) VALUES(?,?,?,?)');
      $data->bindParam(1,$name);
      $data->bindParam(2,$gender);
      $data->bindParam(3,$dob);
      $data->bindParam(4,$email);
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $customer[$OutputData['id']] = array(
          'id'   => $OutputData['id'],
          'name' => $OutputData['name']
        );
      }
      return $customer;
    }

    function Cus_With_Acc($email=NULL,$all=0){
      $customers = array();
      if(isset($email) && $all == 0){
        $data = $this->db->prepare('SELECT customer.id AS c_id, name, email, account.id AS acc_id, balance FROM `customer` LEFT JOIN `account` ON customer.email=account.owner WHERE customer.email=?');
        $data->bindParam(1,$email);
      }
      else if($all == 1 && !isset($email)){
        $data = $this->db->prepare('SELECT customer.id AS c_id, name, email, account.id AS acc_id, balance FROM `customer` LEFT JOIN `account` ON customer.email=account.owner ORDER BY customer.id');
      }
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $customers[$OutputData['c_id']] = array(
          'c_id'    => $OutputData['c_id'],
          'name'    => $OutputData['name'],
          'email'   => $OutputData['email'],
          'acc_id'  => $OutputData['acc_id'],
          'balance' => $OutputData['balance']
        );
      }
      return $customers;
    }

    function Initiate($data){
      $SERVER = new SERVER;
      $CUSTOMER = new CUSTOMER;
      $ACCOUNT = new ACCOUNT;
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['email']) && !empty($_GET['email'])){
          $response = $CUSTOMER->Cus_With_Acc($_GET['email'],0);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else if(isset($_GET['all']) && $_GET['all']==1 && !isset($_GET['email'])){
          $response = $CUSTOMER->Cus_With_Acc(NULL,1);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else {
          $response = $SERVER->ErrorMsg('Results Not Found!');
        }
      }
      else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(
          isset($data->name) && !empty($data->name) &&
          isset($data->gender) && !empty($data->gender) &&
          isset($data->dob) && !empty($data->dob) &&
          isset($data->email) && !empty($data->email)
        ){
          if(empty($CUSTOMER->Select($data->email))){
            $CUSTOMER->Insert($data->name,$data->gender,$data->dob,$data->email);
            $response = $SERVER->SuccessMsg('Customer Created Successfully!');
          }
          else {
            $response = $SERVER->ErrorMsg('Customer Already Exists!');
          }
        }
        else {
          $response = $SERVER->ErrorMsg('Invalid Entries!');
        }
      }
    
      echo json_encode($response,JSON_PRETTY_PRINT);
    }

    function InitialSetup(){
      header('Access-Control-Allow-Origin: *');
      header('Content-Type: application/json');
      $json = file_get_contents('php://input');
      $data = json_decode($json); 
      return $data;
    }
  }

  if(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1) == 'customer.php'){
    $CUSTOMER = new CUSTOMER;
    $cdata = $CUSTOMER->InitialSetup();
    $CUSTOMER->Initiate($cdata);
  }

  
?>