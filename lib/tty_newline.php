<?php

function tty_newline( )
{
  if (posix_isatty(STDOUT))
    echo "\n";
}

?>