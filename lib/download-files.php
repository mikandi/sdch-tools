<?php

// Downloads a batch of files and prepares stripped versions of them

if (!function_exists('curl_init')) {
  echo "cURL for PHP required. Try installing php-curl or php5-curl".PHP_EOL;
  exit(1);
}

if (!isset($argv[1]) || !file_exists($argv[1])) {
  echo "Unable to find folder to save files".PHP_EOL;
  exit(1);
}

$chandle = curl_init();

curl_setopt($chandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chandle, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($chandle, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36");

$cnt = 0;

$allCss = "";

while($cnt < 30) {
  curl_setopt($chandle, CURLOPT_URL, "https://en.wikipedia.org/wiki/Special:Random");
  $page = curl_exec($chandle);
  $info = curl_getinfo($chandle);
  if ($info['http_code'] != 200) {
    exit(2);
  }
  $name = basename($info['url']);
  $name = preg_replace("/%[\\dA-Z]{2}/", "-", $name);
  $name = preg_replace("/\\W/", "", $name);
  echo "Downloaded $name ..".PHP_EOL;
  $dom = new \DOMDocument();
  $loaded = @$dom->loadHTML($page);
  if (!$loaded) continue;
  $external = $dom->getElementsByTagName("link");
  //echo "Found ".$external->length." links on the page".PHP_EOL;
  // Inline CSS
  foreach($external as $link) {
    //echo "External link: ".$link->getAttribute("rel").PHP_EOL;
    if (!$link->hasAttribute("rel") || $link->getAttribute("rel") != "stylesheet") {
      continue;
    }
    $csslink = "https://en.wikipedia.org".$link->getAttribute("href");
    echo "  -> Fetching CSS $csslink".PHP_EOL;
    $csslink = str_replace("&amp;", "&", $csslink);
    curl_setopt($chandle, CURLOPT_URL, $csslink);
    $css = curl_exec($chandle);
    $info = curl_getinfo($chandle);
    if ($info['http_code'] != 200) {
      exit(2);
    }
    $replacement = $dom->createElement("style");
    $csstext = $dom->createTextNode($css);
    $replacement->appendChild($csstext);
    $link->parentNode->replaceChild($replacement, $link);
  }
  if ($cnt == 0) {
    $internal = $dom->getElementsByTagName("style");
    //echo "Found ".$internal->length." inline styles".PHP_EOL;
    foreach ($internal as $style) {
      //echo "Style".PHP_EOL;
      //echo PHP_EOL.$style->nodeValue.PHP_EOL;
      $allCss .= PHP_EOL.$style->nodeValue;
    }
  }
  if ($cnt % 2 == 0 && $cnt < 20) {
    file_put_contents($argv[1]."training-html/".$name.".html", $dom->saveHTML());
    $internal = $dom->getElementsByTagName("style");
    foreach($internal as $style) {
      $style->parentNode->removeChild($style);
    }
    $divs = $dom->getElementsByTagName("div");
    foreach($divs as $div) {
      if ($div->hasAttribute("id") && $div->getAttribute("id") == "content") {
        //echo "Removing Content Node".PHP_EOL;
        $div->parentNode->removeChild($div);
        break;
      }
    }
    file_put_contents($argv[1]."bare-html/".$name.".html", $dom->saveHTML());
  } else {
    file_put_contents($argv[1]."testing-html/".$name.".html", $dom->saveHTML());
  }
  echo ". Done".PHP_EOL;
  $cnt += 1;
}

file_put_contents($argv[1]."merged.css", $allCss);

curl_close($chandle);

exit(0);
