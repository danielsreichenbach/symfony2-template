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
sDevUser=`whoami`

sComposer=""
sNPM=""
sCapistrano=""

sInstall=false
sDatabase=false
sTranslate=false

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
		echo "Composer is not installed or not within PATH"
		exit 1
	fi

	if commandExists npm ; then
		sNPM=`command -v npm`
	else
		echo "Node.js/NPM is not installed or not within PATH"
		exit 1
	fi

	if commandExists cap ; then
		sCapistrano=`command -v cap`
	else
		echo "Capistrano is not installed or not within PATH"
		exit 1
	fi

	if ! [ -d ${DIR}/../node_modules/ ] ; then
		sInstall=true
	elif ! [ -d ${DIR}/../app/Resources/assets/vendor/ ] ; then
		sInstall=true
	elif ! [ -d ${DIR}/../vendor/ ] ; then
		sInstall=true
	fi
}

function applicationOwner ()
{
	sudo chown -R ${sDevUser} ${DIR}/../
}

function applicationCleanup ()
{
	declare -a GENERATED_DIRECTORIES=("app/cache/" "app/logs/" "vendor/" "web/bundles/" "node_modules")
	for sPath in ${GENERATED_DIRECTORIES[@]} ; do
		rm -rf ${DIR}/../${sPath}
	done

	echo "[OK] Generated directories have been removed"
	echo ""
}

function applicationInstall ()
{
	${sNPM} --silent install ${DIR}/../ > /dev/null
	${DIR}/../node_modules/.bin/bower --silent install > /dev/null
	${sComposer} --quiet --no-interaction install > /dev/null

	echo "[OK] External dependencies installed"
	echo ""
}

function applicationBuild ()
{
	${DIR}/../node_modules/.bin/bower --silent install
	${DIR}/../node_modules/.bin/bower --silent prune
	${DIR}/../node_modules/.bin/grunt

	for sEnvironment in dev ; do
		php ${DIR}/../app/console --env=${sEnvironment} --quiet cache:clear
		php ${DIR}/../app/console --env=${sEnvironment} --quiet cache:warmup
		php ${DIR}/../app/console --env=${sEnvironment} --quiet assets:install web --symlink --relative

		echo "[OK] ${sEnvironment} environment built"
		echo ""
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
	exit 1
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
		migrationCount=`ls -1 ${DIR}/../app/migrations/*.php | wc -l`
		fixturesCount=`ls -1 ${DIR}/../src/AppBundle/DataFixtures/ORM/*.php | wc -l`
		php ${DIR}/../app/console --env=${sEnvironment} --quiet doctrine:database:drop --force
		php ${DIR}/../app/console --env=${sEnvironment} --quiet doctrine:database:create
		if [[ ${migrationCount} > 0 ]]; then
			php ${DIR}/../app/console --env=${sEnvironment} --quiet --no-interaction doctrine:migrations:migrate
		else
			php ${DIR}/../app/console --env=${sEnvironment} --quiet --no-interaction doctrine:schema:update --force
		fi
		if [[ ${fixturesCount} > 0 ]]; then
			php ${DIR}/../app/console --env=${sEnvironment} --quiet --no-interaction doctrine:fixtures:load
		fi

		echo "[OK] ${sEnvironment} database prepared"
		echo ""
	done
fi

if [ "${sTranslate}" == "true" ]; then
	for sLocale in en de fr ; do
		php ${DIR}/../app/console translation:update --env=${sEnvironment} --quiet --no-backup --dump-messages --output-format=xlf --force ${sLocale} app

		echo "[OK] ${sLocale} translation updated"
		echo ""
	done
fi

# ${DIR}/../vendor/bin/phpcs --standard=${DIR}/../vendor/escapestudios/symfony2-coding-standard/Symfony2 src
# ${DIR}/../vendor/bin/phpcbf --standard=${DIR}/../vendor/escapestudios/symfony2-coding-standard/Symfony2 src

# --------------------------------------------------------------------------
#  We have to ensure that those are writeable for the server ...
# --------------------------------------------------------------------------
for sPath in ${DIR}/../app/cache/ ${DIR}/../app/logs/ ; do
	mkdir -p ${sPath}
	chmod -R a+rwX  ${sPath}
	setfacl -Rm g:${sWebUser}:rwX ${sPath}
	setfacl -Rm g:${sDevUser}:rwX ${sPath}
done

for sEnvironment in dev ; do
	touch ${DIR}/../app/logs/${sEnvironment}.log
	chmod a+rw ${DIR}/../app/logs/${sEnvironment}.log
	setfacl -m g:${sWebUser}:rw ${DIR}/../app/logs/${sEnvironment}.log
	setfacl -m g:${sDevUser}:rw ${DIR}/../app/logs/${sEnvironment}.log
done
