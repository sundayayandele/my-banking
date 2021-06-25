<?php 

  require_once __DIR__ .'/config.php';
  require_once __DIR__ .'/account.php';
  require_once __DIR__ .'/customer.php';
  require_once __DIR__ .'/transaction.php';

  // rest of the code

  class SERVER {
    public function __construct()
    {
      $this->conn = new CONNECT;
    }
    function CreateObject($class){
      $OBJECT = new $class($this->conn);
      return $OBJECT;
    }

    function TxnHandler(){
      //
    }

    function CustomerHandler(){
      //
    }

    function AccHandler(){
      //
    }

  }

  header('Content-Type: application/json');
  $json = file_get_contents('php://input');
  $data = json_decode($json,true);

  $SERVER = new SERVER;
  $fields = array('first_name' => "please enter first_name",
  'last_name'  => "please enter your last_name");
  foreach ($fields as $field => $error) {
    if(empty($add_data[$field])) {
        $responseJSON = array("Status" => false,"Message" => $error);
        header("content-type:application/json");
        $response = json_encode($responseJSON);
        echo $response; 
    }
}
?>