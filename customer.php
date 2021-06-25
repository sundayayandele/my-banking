<?php 

  class CUSTOMER {
    public function __construct($conn)
    {
      $this->db = $conn;
    }

    function Select($email=NULL){
      $customers = array();
      if(isset($email)){
        $data = $this->db->prepare('SELECT * FROM customer WHERE `email`= ?');
        $data->bindParam(1,$email);
      }
      else {
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
      return json_encode($customers,JSON_PRETTY_PRINT);
    }

    function Insert($name,$gender,$dob,$email){
      $db = new Connect();
      $customer = array();
      $data = $db->prepare('INSERT INTO customer(`name`,`gender`,`dob`,`email`) VALUES(?,?,?,?)');
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
      return json_encode($customer,JSON_PRETTY_PRINT);
    }
  }

  
?>