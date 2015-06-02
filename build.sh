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

sInstall=false
sDatabase=false
sTranslate=false

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
	echo "   -i, --install     Install local packages and generate base files."
	echo "   -d, --database      Create and populate the development database."
    echo "   -t; --translate                              Update translations."
	echo "   -h, --help                                     Display this page."
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

	if ! [ -d ${DIR}/node_modules/ ] ; then
		sInstall=true
	elif ! [ -d ${DIR}/vendor/ ] ; then
		sInstall=true
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
    for sEnvironment in dev ; do
        php ${DIR}/app/console cache:clear --env ${sEnvironment}
        php ${DIR}/app/console cache:warmup --env ${sEnvironment}
        php ${DIR}/app/console assets:install web --symlink --relative --env ${sEnvironment}
    done
}

# --------------------------------------------------------------------------
# Check and environment
# --------------------------------------------------------------------------
verifyRequirements

# --------------------------------------------------------------------------
# Parse command line parameters
# --------------------------------------------------------------------------
for i in "$@"
do
case ${i} in
	-h|--help)
	usage
	exit 0
	;;
	-i|--install)
	sInstall=true
	shift
	;;
	-d|--database)
	sDatabase=true
	shift
	;;
	-t|--translate)
	sTranslate=true
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

if [ "${sInstall}" == "true" ]; then
	applicationCleanup
	applicationInstall
fi

applicationBuild

# --------------------------------------------------------------------------
# Prepare the development environment for a full run of the mill ...
# --------------------------------------------------------------------------
if [ "${sDatabase}" == "true" ]; then
	for sEnvironment in dev ; do
		migrationCount=`ls -1 ${DIR}/app/migrations/*.php | wc -l`
		fixturesCount=`ls -1 ${DIR}/src/AppBundle/DataFixtures/ORM/*.php | wc -l`
		php ${DIR}/app/console doctrine:database:drop --force --env ${sEnvironment}
		php ${DIR}/app/console doctrine:database:create --env ${sEnvironment}
		if [[ ${migrationCount} > 0 ]]; then
			php ${DIR}/app/console doctrine:migrations:migrate -n --env ${sEnvironment}
		fi
		if [[ ${fixturesCount} > 0 ]]; then
			php ${DIR}/app/console doctrine:fixtures:load -n --env ${sEnvironment}
		fi
	done
fi

if [ "${sTranslate}" == "true" ]; then
	for sLocale in en de fr ; do
		php ${DIR}/app/console translation:update --no-backup --dump-messages --output-format=xlf --force ${sLocale}
	done
fi

# ${DIR}/bin/phpcs --standard=${DIR}/vendor/escapestudios/symfony2-coding-standard/Symfony2 src
# ${DIR}/bin/phpcbf --standard=${DIR}/vendor/escapestudios/symfony2-coding-standard/Symfony2 src

# --------------------------------------------------------------------------
#  We have to ensure that those are writeable for the server ...
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
