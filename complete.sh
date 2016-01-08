#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";

./lib/download-html.sh
./build-dictionary.sh
./test-sdch.sh
