<?php 

  require_once __DIR__ .'/config.php';
  class TRANSACTION {
    function SelectAll(){
      $db = new Connect();
      $transactions = array();
      $data = $db->prepare('SELECT * FROM transaction ORDER BY -txn_time');
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
      return json_encode($transactions,JSON_PRETTY_PRINT);
    }
  }

  header('Content-Type: application/json');

  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $TRANSACTION = new TRANSACTION;
    echo $TRANSACTION->SelectAll();
  }
  else {
    echo json_encode('error: GET method is expected!',JSON_PRETTY_PRINT);
  }
  
?>