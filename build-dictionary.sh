#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";

source "${DIR}lib/prepare-deps.sh"

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";

rm -f "${DIR}sdch-css.part"
rm -f "${DIR}sdch-html.part"
DICTS=`ls ${DIR}*.bare`
rm -f $DICTS
DICTS=`ls ${DIR}*.dct`
rm -f $DICTS
rm -f "${DIR}dictionary.config"

if [ ! -f "${DIR}merged.css" ]; then
  echo "ERROR: Missing bare files. Please run ./lib/download-html.sh"
  exit 3
fi

echo "Preparing CSS dictionary .."
./css-dictionary-extract.php
echo " . Done"
echo "Preparing HTML dictionary .."
$FEMTOZIP --model "${DIR}sdch-html.part" --build --dictonly --maxdict 16000 "${DIR}bare-html"
echo " . Done"
echo "Creating final dictionary .."
./create-final-dictionary.php
echo ". Done"
