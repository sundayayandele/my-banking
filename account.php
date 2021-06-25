<?php 

  class ACCOUNT {
    public function __construct($conn)
    {
      $this->db = $conn;
    }

    function Select($email=NULL){
      $accounts = array();
      if(isset($email)){
        $data = $this->db->prepare('SELECT * FROM account WHERE `owner`= ?');
        $data->bindParam(1,$email);
      }
      else {
        $data = $this->db->prepare('SELECT * FROM account ORDER BY id');
      }
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
      return json_encode($account,JSON_PRETTY_PRINT);
    }

    function Update($owner,$balance,$add=false){
      $account = array();
      if($add == true){
        $data = $this->db->prepare('UPDATE account SET `balance`=`balance`+ ? WHERE `owner`= ?');
      }
      else {
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
      return json_encode($account,JSON_PRETTY_PRINT);
    }
  }

?>