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
