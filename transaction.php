<?php 
  $connect = mysqli_connect('localhost','root','','my_bank');
  $response = array();
  if($connect){
    $selectTransaction = 'SELECT * FROM transaction';
    
    $resultTransaction = mysqli_query($connect,$selectTransaction);
    if($resultTransaction){
      header('Content-Type: JSON');
      $index = 0;
      while($row = mysqli_fetch_assoc($resultTransaction)){
        $response[$index]['id'] = $row['id'];
        $response[$index]['sender'] = $row['sender'];
        $response[$index]['amount'] = $row['amount'];
        $response[$index]['reciever'] = $row['reciever'];
        $response[$index]['txn_time'] = $row['txn_time'];
        $index++;
      }
      echo json_encode($response,JSON_PRETTY_PRINT);
    }
  }
  else {
    echo json_encode('ERROR : FAILED TO CONNECT TO DATABASE');
  }
?>