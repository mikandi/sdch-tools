#!/bin/bash

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
