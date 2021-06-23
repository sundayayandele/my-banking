<?php 
  $connect = mysqli_connect('localhost','root','','my_bank');
  $response = array();
  if($connect){
    $selectCustomer = 'SELECT * FROM customer';
    
    $resultCustomer = mysqli_query($connect,$selectCustomer);
    if($resultCustomer){
      header('Content-Type: JSON');
      $index = 0;
      while($row = mysqli_fetch_assoc($resultCustomer)){
        $response[$index]['id'] = $row['id'];
        $response[$index]['name'] = $row['name'];
        $response[$index]['email'] = $row['email'];
        $response[$index]['gender'] = $row['gender'];
        $response[$index]['dob'] = $row['dob'];
        $index++;
      }
      echo json_encode($response,JSON_PRETTY_PRINT);
    }
  }
  else {
    echo json_encode('ERROR : FAILED TO CONNECT TO DATABASE');
  }
?>