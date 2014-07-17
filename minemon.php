#!/usr/bin/php
<?php

require_once "lib/curl_get.php";
require_once "lib/curl_post.php";
require_once "lib/wwwFormUrlEncode.php";

function bladeStatus($blade_ip, $blade_port ) {
  $url = "http://$blade_ip:8000/Main";
  return curl_get($url);
}

function bladeReset($blade,$config) {
  $postfields=array();

  $serverPorts = $config->Ports; 
  if (isset($blade->serverPorts))
     $serverPorts = $blade->serverPorts;

  $postfields["JMIP"] = $blade->ip;
  $postfields["JMSK"] = $config->Mask;
  $postfields["JGTW"] = $config->Gateway;
  $postfields["WPRT"] = $blade->port;
  $postfields["PDNS"] = $config->PrimaryDNS;
  $postfields["SDNS"] = $config->SecondaryDNS;
  $postfields["MPRT"] = $serverPorts;
  $postfields["MURL"] = $config->ServerAddresses;
  $postfields["USPA"] = $config->UserPass;

  $blade_ip = $blade->ip;
  $blade_port = $blade->port;
  $url = "http://$blade_ip:$blade_port/Upload_Data";
  curl_post($url,wwwFormUrlEncode($postfields),null,array('Content-Type'=>'application/x-www-form-urlencoded'));
}

function bladeSpeed($bladeStatus) {
  return preg_replace( "#.*MHS:</td><td align='left'>([0-9]*).*#", "\\1" , $bladeStatus );
}

function bladeChip($bladeStatus) {
  return preg_replace( "#.*Chip: ([^<]*)<form.*#","\\1" , $bladeStatus );
}

function main() {
  $cluster_config = dirname( $_SERVER["SCRIPT_FILENAME"] )."/cluster_config.json";

  if ( !file_exists( $cluster_config ) ) {
    echo "cluster_config.json not found. Please create it and restart.\n";
    echo "Press Ctrl+C to exit.\n";
    while ( true ) {
      sleep(5);
    }
  }
  $conf = json_decode( file_get_contents( $cluster_config ) );
  date_default_timezone_set( 'America/Chicago' );
  ncurses_init();
  $window = ncurses_newwin(0,0,0,0);

  $have_reset = false;
  foreach( $conf->blades as $blade ) {
    if ( isset( $blade->reset ) ) {
       bladeReset( $blade, $conf );
      $have_reset = true;
    }
  }
  if ( $have_reset ) {
    die( "I've reset a blade!" );
  }
  
  foreach( $conf->blades as $blade ) {
    $blade->speedTotal = 0;
    $blade->speedSamples = 0;
    $blade->failCount = -600;
    $blade->resetCount = 0;
    if ( !isset($blade->maxFails) ) $blade->maxFails = 300; // if a blade falls below blade->minSpeed for a full 60 seconds, it's considered stale, and needs to be reset.
    if ( !isset($blade->failcountReset) ) $blade->failcountReset = -600; // after a reset, give an additional 4 minutes for the blade to get up to full speed.
  }

  while( true ) {
    
    foreach( $conf->blades as $blade ) {
      $blade->status = bladeStatus( $blade->ip, $blade->port );
    }
    
    ncurses_clear();
    ncurses_wclear($window);
    ncurses_move( 0, 0 );
    ncurses_refresh();
    $total = 0;
    
    foreach( $conf->blades as $blade ) {
      $speed = bladeSpeed( $blade->status );
      $chip = bladeChip( $blade->status );
      $blade->speedTotal += $speed;
      ++$blade->speedSamples;
      $average = $blade->speedTotal / $blade->speedSamples;
      $total += $speed;
      if ( $speed >= $blade->minSpeed ) {
	$blade->failCount = 0;
      } else {
	++$blade->failCount;
      }
      $failCount = $blade->failCount;
      $resetCount = $blade->resetCount;
      
      echo "blade ".str_pad($blade->id,2," ",STR_PAD_LEFT).": ".str_pad(number_format($speed),7)." [".str_pad(number_format($average),7)." avg] [chip:$chip] [failcount:$failCount] [resetCount:$resetCount]";
      
      if ( $failCount >= $blade->maxFails ) {
	bladeReset( $blade , $conf );
	$blade->failCount = $blade->failcountReset;
	$blade->resetCount++;
      }
      echo "\r\n";
    }

    echo "total:   ".str_pad(number_format($total),7)."\r\n";
    echo "server time:".date('Y-m-d h:i:s')."\r\n";
    sleep(1);
  }
}

main();

?>