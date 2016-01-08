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

rm -rf "${DIR}test-files"

mkdir "${DIR}test-files"
mkdir "${DIR}test-files/testing-vcdiff"
mkdir "${DIR}test-files/testing-gzip"
mkdir "${DIR}test-files/testing-both"
mkdir "${DIR}test-files/training-vcdiff"
mkdir "${DIR}test-files/training-gzip"
mkdir "${DIR}test-files/training-both"

DICT=`ls ${DIR}*.bare`

echo "Using dictionary $DICT"

for file in `ls ${DIR}testing-html`; do
  infile="${DIR}testing-html/${file}"
  echo "  Processing testing file $file"
  $VCDIFF encode -dictionary ${DICT} < $infile > "${DIR}test-files/testing-vcdiff/${file}.vcdiff"
  $GZIP -9 < $infile > "${DIR}test-files/testing-gzip/${file}.gz"
  $GZIP -9 < "${DIR}test-files/testing-vcdiff/${file}.vcdiff" > "${DIR}test-files/testing-both/${file}.sdch"
done

for file in `ls ${DIR}training-html`; do
  infile="${DIR}training-html/${file}"
  echo "  Processing training file $file"
  $VCDIFF encode -dictionary ${DICT} < $infile > "${DIR}test-files/training-vcdiff/${file}.vcdiff"
  $GZIP -9 < $infile > "${DIR}test-files/training-gzip/${file}.gz"
  $GZIP -9 < "${DIR}test-files/training-vcdiff/${file}.vcdiff" > "${DIR}test-files/training-both/${file}.sdch"
done

$PHP "${DIR}lib/prepare-report.php"
