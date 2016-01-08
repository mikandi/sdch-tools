#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";

# Ensures git is installed and sets the bin
function ensuregit() {
  GIT=`which git`
  if [ $? -ne 0 ]; then
    echo "ERROR: Unable to find 'git'. Please install it."
    exit 1
  fi
}

# Fetches the specified git repo
# $1 the human readable repo name
# $2 The git repo to fetch
# $3 The folder/file name for the archive
function getgit {
		echo -n "Preparing $1 ..."
    echo -n "  Fetching git $1 ..."
    if [ ! -d $3 ]; then
        echo -n " Cloning $2 to $3 ..."
        $GIT clone -q $2 $3
        if [ ! $? -eq 0 ]; then
            echo " Failed"; echo "Unable to fetch $1 source ..."
            exit 2;
        fi
    else
        echo -n " Updating $2 source ..."
        pushd $3 > /dev/null
				$GIT reset -q --hard
				$GIT clean -f -d -q
				$GIT checkout -q origin/master
				$GIT reset -q --hard
        $GIT pull -q origin master
        if [ ! $? -eq 0 ]; then
            echo " Failed"; echo "Unable to update $1 source ..."
            exit 2;
        fi
        popd > /dev/null
    fi
    echo -n "."
}

FEMTOZIP=`which fzip`
if [ $? -ne 0 ]; then
  echo -n "FemtoZip not found. Checking for local version.."
  FEMTOZIP="${DIR}femtozip/cpp/fzip/src/fzip"
  if [ ! -f "$FEMTOZIP" ]; then
    echo -n ". Not Found. Building FemtoZip from git"
    ensuregit
    getgit "FemtoZip" "https://github.com/gtoubassi/femtozip" "${DIR}femtozip"
    pushd "${DIR}femtozip/cpp" > /dev/null
    echo ". Building"
    ./configure
    if [ $? -ne 0 ]; then
      echo "ERROR: Unable to configure femtozip. Make sure you have a basic build environment installed?"
      exit 2
    fi
    make
    if [ $? -ne 0 ]; then
      echo "ERROR: Unable to make femtozip. Make sure you have a basic build environment installed?"
      exit 2
    fi
    if [ ! -f "$FEMTOZIP" ]; then
      echo "ERROR: Unable to find femtozip."
      exit 3
    fi
    echo " . Done"
  else
    echo ". Found. Running with local femtozip."
  fi
fi

VCDIFF=`which vcdiff`
if [ $? -ne 0 ]; then
  echo -n "VCDiff not found. Checking for local version.."
  VCDIFF="${DIR}open-vcdiff/vcdiff"
  if [ ! -f "$VCDIFF" ]; then
    echo -n ". Not Found. Building FemtoZip from git"
    ensuregit
    getgit "VCDiff" "https://github.com/google/open-vcdiff" "${DIR}open-vcdiff"
    pushd "${DIR}open-vcdiff" > /dev/null
    echo ". Building"
    ./fetch_prereq.sh
    if [ $? -ne 0 ]; then
      echo "ERROR: Unable to fetch deps for vcdiff. Make sure you have a basic build environment installed?"
      exit 2
    fi
    ./configure
    if [ $? -ne 0 ]; then
      echo "ERROR: Unable to configure vcdiff. Make sure you have a basic build environment installed?"
      exit 2
    fi
    make
    if [ $? -ne 0 ]; then
      echo "ERROR: Unable to make vcdiff. Make sure you have a basic build environment installed?"
      exit 2
    fi
    if [ ! -f "$VCDIFF" ]; then
      echo "ERROR: Unable to find vcdiff."
      exit 3
    fi
    echo " . Done"
  else
    echo ". Found. Running with local vcdiff."
  fi
  echo ""
fi

# echo -n "Fetching php-html-parser .."
# ensuregit
# getgit "PHP HTML Parser" "https://github.com/paquettg/php-html-parser" "${DIR}php-html-parser"
# echo ". Done"

PHP=`which php`
if [ $? -ne 0 ]; then
  echo "ERROR: Missing PHP. Please have a recent version of PHP installed on your system"
  exit 2
fi

# pushd ${DIR} > /dev/null
# if [ ! -f "${DIR}composer.phar" ]; then
#   echo "Installing composer .."
#   ${PHP} -r "readfile('https://getcomposer.org/installer');" | ${PHP}
#   if [ ! -f "${DIR}composer.phar" ]; then
#     echo "ERROR: Unable to install composer"
#     exit 2
#   fi
#   echo ". Done"
# fi
# echo "Installing PHP deps .."
# ${PHP} "-d memory_limit=-1" "${DIR}composer.phar" "self-update"
# ${PHP} "-d memory_limit=-1" "${DIR}composer.phar" "update"
# echo " . Done"
# popd > /dev/null
