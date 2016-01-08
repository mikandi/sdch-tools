<?php

/*
 * Copyright 2015 Innovative Mobile Endeavors, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
  echo str_pad("", $nlen)."  ".str_pad("Original", 10, " ", STR_PAD_BOTH)."  ".str_pad("VCDiff", 16, " ", STR_PAD_BOTH)."  ".str_pad("GZip", 16, " ", STR_PAD_BOTH)."  ".str_pad("Both", 16, " ", STR_PAD_BOTH)."  "."SDCH v.".PHP_EOL;
  echo str_pad("File Name", $nlen, " ", STR_PAD_LEFT)."  ".str_pad("Size", 10, " ", STR_PAD_BOTH)."  ".str_pad("Size", 10, " ", STR_PAD_BOTH)." Saved"."  ".str_pad("Size", 10, " ", STR_PAD_BOTH)." Saved"."  ".str_pad("Size", 10, " ", STR_PAD_BOTH)." Saved"."  "." GZip  ".PHP_EOL;
  $base_total = $vcdiff_total = $gzip_total = $both_total = 0;
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

    $base_total += $base_size;
    $vcdiff_total += $vcdiff_size;
    $gzip_total += $gzip_size;
    $both_total += $both_size;

    echo PHP_EOL;
  }
  $name = "Totals";
  echo str_pad($name, $nlen, " ", STR_PAD_LEFT)."  ";
  echo str_pad(round($base_total/1024, 0)." KB", 10, " ", STR_PAD_LEFT)."  ";

  echo str_pad(round($vcdiff_total/1024, 0)." KB", 10, " ", STR_PAD_LEFT);
  echo str_pad(round((($base_total - $vcdiff_total) / $base_total)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  ";

  echo str_pad(round($gzip_total/1024, 0)." KB", 10, " ", STR_PAD_LEFT);
  echo str_pad(round((($base_total - $gzip_total) / $base_total)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  ";

  echo str_pad(round($both_total/1024, 0)." KB", 10, " ", STR_PAD_LEFT);
  echo str_pad(round((($base_total - $both_total) / $base_total)*100, 1)."%", 6, " ", STR_PAD_LEFT)."  (";
  echo str_pad(round((($gzip_total - $both_total) / $gzip_total)*100, 1)."%", 5, " ", STR_PAD_LEFT).")";
  echo PHP_EOL;
}
