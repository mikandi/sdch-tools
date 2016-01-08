#!/usr/bin/env php
<?php

define("BASEPATH", dirname(__FILE__)."/");
define("DOMAIN", "en.wikipedia.org");
define("PATH", "/");

if (!file_exists(BASEPATH."sdch-css.part") || !file_exists(BASEPATH."sdch-html.part")) {
  echo "Missing required dictionary component. Please regenerate the dictionary parts".PHP_EOL;
  exit(1);
}

$css = file_get_contents(BASEPATH."sdch-css.part");
$html = file_get_contents(BASEPATH."sdch-html.part");

date_default_timezone_set("GMT");
$dict = $css.$html."\nBuilt:".date("Y-m-d_H:i:s");
$head = "Domain: ".DOMAIN."\nPath: ".PATH."\n\n";

echo "Computing dictionary keys".PHP_EOL;

$server_key = substr(rtrim(strtr(base64_encode(hash("sha256", $dict, true)), '+/', '-_'), '='), 0, 8);
$client_key = substr(rtrim(strtr(base64_encode(hash("sha256", $dict, true)), '+/', '-_'), '='), 8, 8);

file_put_contents(BASEPATH.$server_key.".dct", $head.$dict);
file_put_contents(BASEPATH.$server_key.".bare", $dict);
file_put_contents(BASEPATH."dictionary.config", "server_key:$server_key\nclient_key:$client_key\n");

exit(0);
