#!/bin/bash

# Copyright 2015 Innovative Mobile Endeavors, LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#        http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

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
