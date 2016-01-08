#!/usr/bin/env php
<?php

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
