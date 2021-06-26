<?php 
  require_once __DIR__ .'/config.php';
  require_once __DIR__ .'/server.php';
  require_once __DIR__ .'/customer.php';

  class ACCOUNT {
    public function __construct(){
      $this->db = new Connect;
    }

    function Select($email=NULL,$bal=0,$all=0){
      if(isset($email) && $bal==0){
        $data = $this->db->prepare('SELECT * FROM account WHERE `owner`= ?');
        $data->bindParam(1,$email);
      }
      else if(!isset($email) && $all==1){
        $data = $this->db->prepare('SELECT * FROM account ORDER BY id');
      }
      else if(isset($email) && $bal==1){
        $data = $this->db->prepare('SELECT `balance` FROM account WHERE `owner`= ?');
        $data->bindParam(1,$email);
        $data->execute();
        $OutputData = $data->fetch(PDO::FETCH_ASSOC);
        return $OutputData['balance'];
      }
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $accounts[$OutputData['id']] = array(
          'id'      => $OutputData['id'],
          'owner'   => $OutputData['owner'],
          'balance' => $OutputData['balance']
        );
      }
      return $accounts;
    }

    function Insert($owner,$balance){
      $account = array();
      $data = $this->db->prepare('INSERT INTO account(`owner`,`balance`) VALUES(?,?)');
      $data->bindParam(1,$owner);
      $data->bindParam(2,$balance);
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $account[$OutputData['id']] = array(
          'id'      => $OutputData['id'],
          'owner'   => $OutputData['owner'],
          'balance' => $OutputData['balance']
        );
      }
      return $account;
    }

    function Update($owner,$balance,$action=-1){
      $account = array();
      if($action == 1){
        $data = $this->db->prepare('UPDATE account SET `balance`=`balance`+ ? WHERE `owner`= ?');
      }
      else if($action == -1){
        $data = $this->db->prepare('UPDATE account SET `balance`=`balance`- ? WHERE `owner`= ?');
      }
      $data->bindParam(1,$balance);
      $data->bindParam(2,$owner);
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $account[$OutputData['id']] = array(
          'id'      => $OutputData['id'],
          'owner'   => $OutputData['owner'],
          'balance' => $OutputData['balance']
        );
      }
      return $account;
    }

    function Initiate($data){
      $SERVER = new SERVER;
      $CUSTOMER = new CUSTOMER;
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['email']) && !empty($_GET['email'])){
          $response = $this->Select($_GET['email'],0);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else if(isset($_GET['all']) && $_GET['all']==1){
          $response = $this->Select(NULL,0,1);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else {
          $response = $SERVER->ErrorMsg('Results Not Found!');
        }
      }
      else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($data->email) && !empty($data->email) && isset($data->balance) && (is_float($data->balance) || is_int($data->balance))){ 
          $response = $this->Select($data->email,0);
          if(!empty($response)){
            $response = $SERVER->ErrorMsg('Account Already Exists!');
          }
          else {
            $response = $CUSTOMER->Select($data->email,0);
            if(!empty($response)){
              $this->Insert($data->email,$data->balance);
              $response = $SERVER->SuccessMsg('Account Created Successfully!');
            }
            else {
              $response = $SERVER->ErrorMsg('Customer Not Exists!');
            }
          }
        }
        else {
          $response = $SERVER->ErrorMsg('Invalid Entries!');
        }
      }
      else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
        if(isset($data->email) && !empty($data->email) && isset($data->amount) && (is_float($data->amount) || is_int($data->amount))){
          $this->Update($data->email,$data->amount,1);
          $response = $SERVER->SuccessMsg('Money Added Successfully!');
        }
        else {
          $response = $SERVER->ErrorMsg('Invalid Entries!');
        }
      }
      else {
        $response = $SERVER->ErrorMsg('Method Not Allowed!');
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
 
  if(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1) == 'account.php'){
    $ACCOUNT = new ACCOUNT;
    $adata = $ACCOUNT->InitialSetup();
    $ACCOUNT->Initiate($adata);
  }

?>