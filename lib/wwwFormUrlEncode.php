<?php

// application/x-www-form-urlencoded
function wwwFormUrlEncode($fields) {
  $result = "";
  foreach($fields as $key=>$value)
    $result .= urlencode($key).'='.urlencode($value).'&';
  return rtrim($result);
}

?>