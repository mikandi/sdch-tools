#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";
BASEDIR="${DIR}../"
source ${DIR}prepare-deps.sh

rm -rf "${BASEDIR}bare-html"
rm -rf "${BASEDIR}training-html"
rm -rf "${BASEDIR}testing-html"
rm -f "${BASEDIR}merged.css"

mkdir "${BASEDIR}bare-html"
mkdir "${BASEDIR}training-html"
mkdir "${BASEDIR}testing-html"

${PHP} ${DIR}download-files.php $BASEDIR
