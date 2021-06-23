<?php 
  $connect = mysqli_connect('localhost','root','','my_bank');
  $response = array();
  if($connect){
    $selectAccount = 'SELECT * FROM Account';
    
    $resultAccount = mysqli_query($connect,$selectAccount);
    if($resultAccount){
      header('Content-Type: JSON');
      $index = 0;
      while($row = mysqli_fetch_assoc($resultAccount)){
        $response[$index]['id'] = $row['id'];
        $response[$index]['owner'] = $row['owner'];
        $response[$index]['balance'] = $row['balance'];
        $index++;
      }
      echo json_encode($response,JSON_PRETTY_PRINT);
    }
  }
  else {
    echo json_encode('ERROR : FAILED TO CONNECT TO DATABASE');
  }
?>