<?php

// Prepares a report on the compression properties of the SDCH test

define("BASEPATH", dirname(__FILE__)."/../");

if (!file_exists(BASEPATH."test-files") || !file_exists(BASEPATH."testing-html") || !file_exists(BASEPATH."training-html")) {
  echo "Missing test files. Please run test-sdch.sh and try again.".PHP_EOL;
  exit(1);
}

$types = array("testing", "training");

foreach($types as $type) {
  echo ucfirst($type)." Files".PHP_EOL;
  $files = glob(BASEPATH.$type."-html/*.html");
  $nlen = 0;
  foreach($files as $file) {
    $nlen = max($nlen, strlen(basename($file)));
  }
  foreach($files as $file) {
    $name = basename($file);
    echo str_pad($name, $nlen, " ", STR_PAD_LEFT)."  ";
    $base_size = filesize($file);
    echo str_pad(round($base_size/1024, 1)." KB", 10, " ", STR_PAD_LEFT)."  ";

    $vcdiff_size = filesize(BASEPATH."test-files/$type-vcdiff/$name.vcdiff");
    echo str_pad(round($vcdiff_size/1024, 1)." KB", 10, " ", STR_PAD_LEFT);
    echo str_pad(round((($base_size - $vcdiff_size) / $base_size)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  ";

    $gzip_size = filesize(BASEPATH."test-files/$type-gzip/$name.gz");
    echo str_pad(round($gzip_size/1024, 1)." KB", 10, " ", STR_PAD_LEFT);
    echo str_pad(round((($base_size - $gzip_size) / $base_size)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  ";

    $both_size = filesize(BASEPATH."test-files/$type-both/$name.sdch");
    echo str_pad(round($both_size/1024, 1)." KB", 10, " ", STR_PAD_LEFT);
    echo str_pad(round((($base_size - $both_size) / $base_size)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  (";
    echo str_pad(round((($gzip_size - $both_size) / $gzip_size)*100, 1)."%", 5, " ", STR_PAD_LEFT).")";

    echo PHP_EOL;
  }
  echo PHP_EOL;
}
