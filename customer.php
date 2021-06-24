<?php 

  require_once __DIR__ .'/config.php';
  class CUSTOMER {
    function SelectAll(){
      $db = new Connect();
      $customers = array();
      $data = $db->prepare('SELECT * FROM customer ORDER BY id');
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

  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $CUSTOMER = new CUSTOMER;
    echo $CUSTOMER->SelectAll();
  }
  else {
    echo json_encode('error: GET method is expected!',JSON_PRETTY_PRINT);
  }
  
?>