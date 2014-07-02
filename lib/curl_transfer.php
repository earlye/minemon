<?php

require_once "entry.php";
require_once "http_result_strings.php";

function curl_transfer( $url , $curloptions )
{
  global $config;

  $verbose = isset( $config->curl ) && $config->curl->verbose;

  if ( $verbose )
    {
      $method = entry( $curloptions, CURLOPT_CUSTOMREQUEST, "GET" );
      $data = entry( $curloptions, CURLOPT_POSTFIELDS , "" );
      $parsed_url = parse_url( $url );
      $path = $parsed_url['path'];
      $query = $parsed_url['query'];
      $uri = $path;
      if ( strlen( $query ) )
	$uri .= "?$query";
      echo "Request:\n<pre>\n";
      echo "$method $uri HTTP/1.1\n";
      foreach( entry( $curloptions, CURLOPT_HTTPHEADER , array() ) as $header )
	echo "$header\n";
      echo "\n$data\n";

      $header_file = fopen("php://temp/maxmemory:$fiveMBs", 'w');
      if ( !isset($curloptions[CURLOPT_WRITEHEADER]) )
	{
	  $curloptions[CURLOPT_WRITEHEADER] = $header_file;
	  $curloptions[CURLINFO_HEADER_OUT] = true;
	  $curloptions[CURLOPT_HEADER] = true;
	}
      echo "</pre>\n";
    }

  $curl = curl_init( $url );
  foreach( $curloptions as $id => $value )
    {
      curl_setopt( $curl , $id , $value );
    }

  $result = curl_exec( $curl );

  if ( $verbose )
    {
      $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
      $headers = substr($result, 0, $header_size);
      $result  = substr($result, $header_size);

      echo "Response:\n<pre>\n";
      echo "$headers";
      echo "$result\n";
      echo "</pre>\n";
    }

  return $result;
}

?>