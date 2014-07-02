<?php

require_once "curl_transfer.php";

function curl_delete( $url , $options = null , $headers = null )
{
  if ( $headers == null )
     $headers = array();

  $curlopt_httpheader = array();
  foreach( $headers as $header => $value )
  {
    array_push( $curlopt_httpheader , "$header: $value" );
  }

  if ( !isset( $options[CURLOPT_CUSTOMREQUEST] ) ) $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
  if ( !isset( $options[CURLOPT_RETURNTRANSFER] ) ) $options[CURLOPT_RETURNTRANSFER] = true;
  if ( !isset( $options[CURLOPT_HTTPHEADER] ) ) $options[CURLOPT_HTTPHEADER] = $curlopt_httpheader;

  $result = curl_transfer( $url , $options );
  return $result;
}

?>