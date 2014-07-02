<?php

require_once "curl_transfer.php";

function curl_get( $url , $options = null , $headers = null )
{
  if ( $headers == null )
     $headers = array();
  if ( $options == null )
     $options = array();

  if ( !isset( $headers["Accept"] ) ) $headers["Accept"] = "application/json";

  $curlopt_httpheader = array();
  foreach( $headers as $header => $value )
  {
    array_push( $curlopt_httpheader , "$header: $value" );
  }

  if ( !isset($options[CURLOPT_HTTPHEADER] ) ) $options[CURLOPT_HTTPHEADER] = $curlopt_httpheader;
  if ( !isset($options[CURLOPT_RETURNTRANSFER] ) ) $options[CURLOPT_RETURNTRANSFER] = true;

  return curl_transfer( $url , $options );
}

?>