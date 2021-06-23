<?php 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $connect = mysqli_connect('localhost','root','','my_bank');
    $getSender = 'SELECT email FROM customer WHERE name = ' + $_POST['sender'];
    $getReciever = 'SELECT email FROM customer WHERE name = ' + $_POST['reciever'];
    if($getSender && $getReciever){
      $getAmount = 'SELECT amount from account WHERE owner = ' + $getSender;
      if($getAmount < $_POST['amount']){
        echo json_encode('{error: Insufficient Account Balance!}');
      }
      else {
        $newtxn = 'INSERT INTO `transaction`(`sender`, `reciever`, `amount`) VALUES (' + $getSender + ',' + $getReciever + ',' + $_POST['amount'] + ')';
        $result = mysqli_query($connect,$newtxn);
        if($result){
          echo json_encode('{success: Transfered Successfully!}');
        }
        else {
          echo json_encode('{error: Transaction Failed!}');
        }
      }
    }
    else {
      echo json_encode("{error: Sender Or Reciever Didn't Exists}");
    }
  }
  else {
    echo json_encode('{error: Invalid Request!}');
  }
?>