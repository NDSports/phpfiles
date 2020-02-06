<?php
function sendMail($from, $to, $cc, $bcc, $subject, $message)
{
  $fromsent = array();
  if (is_array($from)) {
    foreach ($from as $key => $value) {
      $fromsent[]=  $key. " <" .$value.">";
    }
  }
  $from  = implode(',', $fromsent);

  $tosent = array();
  if (is_array($to)) {
    foreach ($to as $key => $value) {
      $tosent[]=  $key. " <" .$value.">";
    }
  }
  $to  = implode(',', $tosent);

  $ccsent = array();
  if (is_array($cc)) {
    foreach ($cc as $key => $value) {
      $ccsent[]=  $key. " <" .$value.">";
    }
  }
  $cc  = implode(',', $ccsent);

  $bccsent = array();
  if (is_array($bcc)) {
    foreach ($bcc as $key => $value) {
      $bccsent[]=  $key. " <" .$value.">";
    }
  }
  $bcc  = implode(',', $bccsent);
  
  // To send HTML mail, the Content-type header must be set
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: <www.trunkpool.com>' . "\r\n";

  // Additional headers
  if (strlen($from)>0) {
    $headers .= 'From: ' .$from. "\r\n";
  }
  // $headers.="<br/>";
  if (strlen($to)>0) {
    $headers .= 'To: ' .$to. "\r\n";
  }
  // $headers.="<br/>";
  if (strlen($cc)>0) {
    $headers .= 'Cc: ' .$cc. "\r\n";
  }
  // $headers.="<br/>";
  if (strlen($bcc)>0) {
    $headers .= 'Bcc: ' .$bcc. "\r\n";
  }
  if (mail($to, $subject, $message, $headers)) {
    return 1;
  }else{
    return 0;
  }
}
?>