args="$@";

for arg in ${args}
do
	if [[ "${arg}" == "-h" ]] || [[ "${arg}" == "--help" ]]; then
		echo -e "\nUsage: bash cdn.sh [options]\n";
		echo -e "Options:\n";
		echo -e "  -h, --help: Display the help.";
		echo -e "  -u, --update: Check for updates.";
		echo -e "  -d, --debug: Force the upload of the files.";
		echo -e "  -f, --force: Display the debug messages.\n";
		exit 0;
	elif [[ "${arg}" == "-u" ]] || [[ "${arg}" == "--update" ]]; then
		exit 0;
	elif [[ "${arg}" == "-f" ]] || [[ "${arg}" == "--force" ]]; then
		force="true";
	elif [[ "${arg}" == "-d" ]] || [[ "${arg}" == "--debug" ]]; then
		debug="true";
	else
		force="false";
		debug="false";
	fi
done