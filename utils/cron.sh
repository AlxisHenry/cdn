# This script is made to set a cron task used to save your shared files
# To configure the cron, change the variable below:

source utils/tools.sh;

# Path to shared folder
shared="/xxx/xxx/public/shared";

# Save folder path
saved="/xxx/xxx/cdn_shared_saves";

function crontab() {
	if [[ ! -d ${saved} ]]; then
		throw "Save folder specified [ ${saved} ] does not exist" true;
	elif [ "${EUID}" -ne 0 ]; then 
		throw "Please, run this script as root" true;
	fi
	cron="/etc/cron.hourly/cdn-autosave";
	echo "#!/bin/bash
cp -r ${shared} ${saved}/cdn_save_\$(date +%s)" > ${cron}
	sudo chmod +x ${cron}
}

crontab;