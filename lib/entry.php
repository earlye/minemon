<?php

function entry( $array , $index , $default = null )
{
  if ( !isset( $array[$index] ) )
    return $default;
  return $array[$index];
}

?>