<?php 

  require_once __DIR__ .'/config.php';
  class NEXTXN {
    function CreateTXN($sender,$reciever,$amount){
      $db = new Connect();
      $customers = array();
      $data = $db->prepare('INSERT INTO transaction(`sender`,`amount`,`reciever`) VALUES('.$sender.','.$amount.','.$reciever.');');
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
      return json_encode($customers,JSON_PRETTY_PRINT);
    }
  }

  header('Content-Type: application/json');

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $NEXTXN = new NEXTXN;
    echo $NEXTXN->CreateTXN($sender,$reciever,$amount);
  }
  else {
    echo json_encode('error: POST method is expected!',JSON_PRETTY_PRINT);
  }

?>