<?php 

  class TRANSACTION {
    public function __construct($conn)
    {
      $this->db = $conn;
    }

    function Select($email=NULL){
      $transactions = array();
      if(isset($email)){
        $data = $this->db->prepare('SELECT * FROM transaction WHERE `sender` = ? OR `reciever` = ?');
        $data->bindParam(1, $email);
        $data->bindParam(2, $email);
      }
      else {
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
      return json_encode($transactions,JSON_PRETTY_PRINT);
    }

    function Insert($sender,$reciever,$amount){
      $transaction = array();
      $data = $this->db->prepare('INSERT INTO transaction(`sender`,`amount`,`reciever`) VALUES(?,?,?)');
      $data->bindParam(1,$sender);
      $data->bindParam(2,$reciever);
      $data->bindParam(3,$amount);
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
      return json_encode($transaction,JSON_PRETTY_PRINT);
    }
  }
  
?>