#!/bin/bash
#
# This script helps keeping the project in a pristine state, in which it can
# be installed and configured in both development and deployment mode.#
#

# --------------------------------------------------------------------------
# Prepare required variables
# --------------------------------------------------------------------------
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMP_FOLDER=${TMPDIR-/tmp}
sWebUser=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`
sComposer=""
sNPM=""
sCapistrano=""

# --------------------------------------------------------------------------
# Source CLI helpers
# --------------------------------------------------------------------------
. ./libui.sh
libui_sh_init "cli"

# --------------------------------------------------------------------------
# Helper functions
# --------------------------------------------------------------------------
function usage()
{
	echo "Usage: " $(basename "${BASH_SOURCE[0]}") " [OPTIONS]"
	echo
	echo "Prepares the build tree to present a clean environment.             "
	echo
	echo "   -c, --clean           Removes local packages and generated files."
	echo "   -i, --install     Install local packages and generate base files."
	echo "   -b, --build                             Build and install assets."
	echo
	echo "Example:"
	echo "  " $(basename "${BASH_SOURCE[0]}") " -i"
	echo
}

function commandExists ()
{
	command -v "$1" &> /dev/null ;
}

function verifyRequirements ()
{
	if commandExists composer ; then
		sComposer=`command -v composer`
	else
		die_error "Composer is not installed or not within PATH"
	fi

	if commandExists npm ; then
		sNPM=`command -v npm`
	else
		die_error "Node.js/NPM is not installed or not within PATH"
	fi

	if commandExists cap ; then
		sCapistrano=`command -v cap`
	else
		die_error "Capistrano is not installed or not within PATH"
	fi
}

function applicationOwner ()
{
	sudo chown -R `whoami`.`whoami` ${DIR}/
}

function applicationCleanup ()
{
	declare -a GENERATED_DIRECTORIES=("app/cache/" "app/logs/" "bin/" "vendor/" "web/bundles/" "node_modules")
	for sPath in ${GENERATED_DIRECTORIES[@]} ; do
		rm -rf ${DIR}/${sPath}
	done
}

function applicationInstall ()
{
	${sNPM} install
	${sComposer} install
}

function applicationBuild ()
{
	${DIR}/node_modules/.bin/grunt
}

# --------------------------------------------------------------------------
# Check number of parameters and environment
# --------------------------------------------------------------------------
if [[ $# = 0 ]]; then
    usage
    die_error "Please chose a mode to continue."
fi

verifyRequirements

# --------------------------------------------------------------------------
# Parse command line parameters
# --------------------------------------------------------------------------
for i in "$@"
do
case $i in
	-c|--clean)
	sClean=true
	shift
	;;
	-i|--install)
	sInstall=true
	shift
	;;
	-b|--build)
	sBuild=true
	shift
	;;
	*)
	usage
	die_error "Unknown parameter specified"
	;;
esac
done

# --------------------------------------------------------------------------
# Rebuilding application data ...
# --------------------------------------------------------------------------
applicationOwner

if [ "${sClean}" == "true" ]; then
	applicationCleanup
fi

if [ "${sInstall}" == "true" ]; then
	applicationInstall
fi

if [ "${sBuild}" == "true" ]; then
	applicationBuild
fi

# --------------------------------------------------------------------------
# Prepare the development environment for a full run of the mill ...
# --------------------------------------------------------------------------
for sEnvironment in dev ; do
	php ${DIR}/app/console cache:clear --env ${sEnvironment}
	php ${DIR}/app/console cache:warmup --env ${sEnvironment}
	php ${DIR}/app/console assets:install web  --symlink --relative --env ${sEnvironment}
done

# --------------------------------------------------------------------------
#  We have to ensure that those are writable for the server ...
# --------------------------------------------------------------------------
for sPath in ${DIR}/app/cache/ ${DIR}/app/logs/ ; do
	mkdir -p ${sPath}
	chmod -R a+rwX  ${sPath}
	setfacl -Rm g:${sWebUser}:rwX ${sPath}
done

for sEnvironment in dev ; do
	touch ${DIR}/app/logs/${sEnvironment}.log
	chmod a+rw ${DIR}/app/logs/${sEnvironment}.log
	setfacl -m g:${sWebUser}:rw ${DIR}/app/logs/${sEnvironment}.log
done
