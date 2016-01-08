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

echo "Extracting common substrings from compressed CSS files".PHP_EOL;

define("BASEPATH", dirname(__FILE__)."/");

$keys = array();
$data = file_get_contents(BASEPATH."merged.css");
processFile($data, $keys);

$saved = 0;
$out = "";
foreach($keys as $key => $ocurrences) {
        if ($ocurrences > 1 || strlen($key) >= 16) {
                $saved += 1;
                $out .= $key;
        }
}
file_put_contents(BASEPATH."sdch-css.part", $out);
echo "Compressed a total of $saved tokens".PHP_EOL;

function processFile($data, &$keys) {
    $state = "css";
    $ptr = 0;
    $last = -1;
    $len = strlen($data);
    $key = null;
    while($ptr < $len) {
        switch($state) {
            case "css":
                if ($data[$ptr] == '}') {
                    $last = $ptr;
                }
                if ($data[$ptr] == ',') {
                    $key = substr($data, $last + 1, $ptr - $last);
                }
                if ($data[$ptr] == '{') {
                    $key = substr($data, $last + 1, $ptr - $last);
                    $state = "rule";
                }
                break;
            case "rule":
                if ($data[$ptr] == '}') {
                    $state = "css";
                    $key = substr($data, $last + 1, $ptr - $last);
                }
                if ($data[$ptr] == ';') {
                    $key = substr($data, $last + 1, $ptr - $last);
                }
                break;
            default:
                die("ERROR: Unknown parsing state");
        }
        if ($key != null && strlen($key) > 6) {
            if (!isset($keys[$key])) {
                $keys[$key] = 0;
            }
            $keys[$key] += 1;
            $last = $ptr;
            $key = null;
        }
        $ptr += 1;
    }
}
