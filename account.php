<?php 

  require_once __DIR__ .'/config.php';
  class ACCOUNT {
    function SelectAll(){
      $db = new Connect();
      $accounts = array();
      $data = $db->prepare('SELECT * FROM account ORDER BY id');
      $data->execute();
      while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
        $accounts[$OutputData['id']] = array(
          'id'      => $OutputData['id'],
          'owner'   => $OutputData['owner'],
          'balance' => $OutputData['balance']
        );
      }
      return json_encode($accounts,JSON_PRETTY_PRINT);
    }
  }

  header('Content-Type: application/json');

  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $ACCOUNT = new ACCOUNT;
    echo $ACCOUNT->SelectAll();
  }
  else {
    echo json_encode('error: GET method is expected!',JSON_PRETTY_PRINT);
  }

?>