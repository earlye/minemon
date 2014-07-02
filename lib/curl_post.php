<?php

require_once "curl_transfer.php";

function curl_post( $url , $data , $options = null , $headers = null )
{
  if ( $headers == null )
     $headers = array();

  if ( !isset( $headers["Content-Type"] ) ) $headers["Content-Type"] = "application/json";
  if ( !isset( $headers["Content-Length"] ) ) $headers["Content-Length"] = strlen($data);

  $curlopt_httpheader = array();
  foreach( $headers as $header => $value )
  {
    array_push( $curlopt_httpheader , "$header: $value" );
  }

  if ( !isset( $options ) ) $options = array();
  if ( !isset( $options[CURLOPT_CUSTOMREQUEST] ) ) $options[CURLOPT_CUSTOMREQUEST] = "POST";
  if ( !isset( $options[CURLOPT_POSTFIELDS] ) ) $options[CURLOPT_POSTFIELDS] = $data;
  if ( !isset( $options[CURLOPT_RETURNTRANSFER] ) ) $options[CURLOPT_RETURNTRANSFER] = true;
  if ( !isset( $options[CURLOPT_HTTPHEADER] ) ) $options[CURLOPT_HTTPHEADER] = $curlopt_httpheader;

  $result = curl_transfer( $url , $options );
  return $result;
}

?>