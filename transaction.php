<?php 
  require_once __DIR__ .'/config.php';
  require_once __DIR__ .'/server.php';
  require_once __DIR__ .'/account.php';

  class TRANSACTION {
    public function __construct(){
      $this->db = new Connect;
    }

    function Select($email=NULL,$all=0){
      $transactions = array();
      if(isset($email)){
        $data = $this->db->prepare('SELECT * FROM transaction WHERE `sender` = ? OR `reciever` = ?');
        $data->bindParam(1, $email);
        $data->bindParam(2, $email);
      }
      else if($all == 1){
        $data = $this->db->prepare('SELECT * FROM transaction ORDER BY -txn_time');
      }
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $transactions[$OutputData['id']] = array(
          'id'       => $OutputData['id'],
          'sender'   => $OutputData['sender'],
          'amount'   => $OutputData['amount'],
          'reciever' => $OutputData['reciever'],
          'txn_time' => $OutputData['txn_time']
        );
      }
      return $transactions;
    }

    function Insert($sender,$reciever,$amount){
      $transaction = array();
      $data = $this->db->prepare('INSERT INTO transaction(`sender`,`amount`,`reciever`) VALUES(?,?,?)');
      $data->bindParam(1,$sender);
      $data->bindParam(2,$amount);
      $data->bindParam(3,$reciever);
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $transaction[$OutputData['id']] = array(
          'id'       => $OutputData['id'],
          'sender'   => $OutputData['sender'],
          'amount'   => $OutputData['amount'],
          'reciever' => $OutputData['reciever'],
          'txn_time' => $OutputData['txn_time']
        );
      }
      return $transaction;
    }

    function Initiate($data){
      $SERVER = new SERVER;
      $ACCOUNT = new ACCOUNT;
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['email']) && !empty($_GET['email'])){
          $response = $this->Select($_GET['email'],0);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else if(isset($_GET['all']) && $_GET['all']==1){
          $response = $this->Select(NULL,1);
          if(empty($response)){
            $response = $SERVER->ErrorMsg('Results Not Found!');
          }
        }
        else {
          $response = $SERVER->ErrorMsg('Results Not Found!');
        }
      }
      else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($data->sender) && isset($data->reciever) && isset($data->amount)){
    
          $bal = $ACCOUNT->Select($data->sender,1);
    
          if($data->amount <= $bal){
    
            if(empty($ACCOUNT->Update($data->sender,$data->amount,-1))){
              if(empty($ACCOUNT->Update($data->reciever,$data->amount,1))){
                if(empty($this->Insert($data->sender,$data->reciever,$data->amount))){
                  $response = $SERVER->SuccessMsg('Transaction Successful!');
                }
                else {
                  $response = $SERVER->ErrorMsg('Transaction Statement Cannot Be Updated!');
                }
              }
              else {
                if(empty($ACCOUNT->Update($data->sender,$data->amount,1))){
                  $response = $SERVER->ErrorMsg('Not Able To Transfer At This Moment!');
                }
              }
            }
            else {
              $response = $SERVER->ErrorMsg('Transaction Failed Due To Amount Cannot Be Deducted!');
            }
    
          }
          else {
            $response = $SERVER->ErrorMsg('Insufficient Account Balance!');
          }
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

  if(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1) == 'transaction.php'){
    $TRANSACTION = new TRANSACTION;
    $tdata = $TRANSACTION->InitialSetup();
    $TRANSACTION->Initiate($tdata);
  }
  
?>