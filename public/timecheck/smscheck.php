<?php 
 

//if ( ! function_exists('send_sms_to_mobile')) {
    function send_sms_to_mobile($mobile="971524969853",$msg="hello------------"){
 
        $curl = curl_init();

        curl_setopt_array($curl, array(
         // CURLOPT_URL => 'http://51.210.118.93:8080/websmpp/websms?accesskey=OUXGeigVjbTet6J&sid=Urbanmop&mno=971'.$mobile.'&text='.$msg,

         // CURLOPT_URL => 'http://51.210.118.93:8080/websmpp/websms?accesskey=OUXGeigVjbTet6J&sid=Urbanmop&mno=971'.$mobile.'&text='.$msg,

          CURLOPT_URL => 'http://51.210.118.93:8080/websmpp/websms?accesskey=OUXGeigVjbTet6J&sid=Urbanmop&mno=971'.$mobile.'&text='.$msg,
          
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        print_r($response);
        
    }
//}

send_sms_to_mobile($_GET["mobile"],$_GET["msg"]);
?>