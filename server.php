<?php 

  class SERVER {

    function ErrorMsg($msg=NULL){
      if(isset($msg)){
        $response = array(
          'status' => false,
          'message'=> $msg
        );
      }
      else {
        $response = array(
          'status' => false,
          'message'=> 'Something Went Wrong!'
        );
      }
      return $response;
    }

    function SuccessMsg($msg=NULL){
      if(isset($msg)){
        $response = array(
          'status' => true,
          'message'=> $msg
        );
      }
      else {
        $response = array(
          'status' => true,
          'message'=> 'Request Successful!'
        );
      }
      return $response;
    }

  }

  $SERVER = new SERVER;
  
?>