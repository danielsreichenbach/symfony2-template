#!/bin/bash

# --------------------------------------------------------------------------
# Prepare required variables
# --------------------------------------------------------------------------
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMP_FOLDER=${TMPDIR-/tmp}
sWebUser=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`
sComposer=""

# --------------------------------------------------------------------------
# Helper functions
# --------------------------------------------------------------------------
function commandExists ()
{
	command -v "$1" &> /dev/null ;
}

function verifyRequirements ()
{
	if commandExists composer ; then
		sComposer=`command -v composer`
	else
		exit 1
	fi
}

function applicationOwner ()
{
	sudo chown -R `whoami`.`whoami` ${DIR}/
}

function applicationInstall ()
{
	declare -a GENERATED_DIRECTORIES=("app/cache/" "app/logs/" "bin/" "vendor/" "web/bundles/")
	for sPath in ${GENERATED_DIRECTORIES[@]} ; do
		rm -rf ${DIR}/${sPath}
	done
	${sComposer} install
}

# --------------------------------------------------------------------------
# Rebuilding application data ...
# --------------------------------------------------------------------------
verifyRequirements
applicationOwner

if [ "$1" = "full" ] ; then
	applicationInstall
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
