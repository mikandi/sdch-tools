#!/usr/bin/env php
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
