function upload() {	
	if [[ "${debug}" == "true" ]]; 
	then
		debug "Ssh credentials: ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} -t ${SSH_DIST}";
	fi
	ssh ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "sudo chown -R ${SSH_USER}:${SSH_USER} ${SSH_DIST}/${category}";
	rsync -azP -e "ssh -p ${SSH_PORT}" uploads/${formatted_filename} ${SSH_USER}@${SSH_ADDR}:${SSH_DIST}/${category}/ > /dev/null;
	ssh ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "sudo chown -R www-data:www-data ${SSH_DIST}/${category}";
}

function filenameCorrespondance() {
	clear;
	echo -e "Github: https://github.com/AlxisHenry/cdn \n";
	echo -n "Retrieving files"; wait;
	if [ -z "${files}" ]; then
		throw "No files found in \033[36muploads\033[0m.";
	fi
	echo -e "\nWe found the following files in \033[36muploads\033[0m :\n"
	for file in ${files}
	do
		filename=$(echo ${file} | cut -d '/' -f 2);
		echo -e "- ${filename}";
	done
	warning "For safety reasons be sure you doens't have any special characters in the name of the file." true;
	warning "Please be sure you doens't have any special characters in the name of the file." false;
	warning "Files will be renamed to lowercase and without spacing." false;
	echo -n -e "\nDo you want to continue ? (y/n) [\033[0;33my\033[0m]: "; read response;
	case $response in
		[n/N]) echo -e "\n"; exit 0;;
		*) ;;
	esac
}

function fileRequirements() {
	if [ ! -f "${file}" ]; then
		throw "File < ${file} > not found";
	fi
	if [[ "${force}" == "false" ]]; then
		if [ ! -s "${file}" ]; then
			throw "File < ${file} > is empty";
		elif [ $(wc -c < "${file}") -gt ${max_size_file} ]; then
			throw "File < ${file} > is too big";
		fi
		success "File < ${file} > matches the requirements." true;
	else
		success "File < ${file} > matches the requirements.";
	fi
}

function fileCategory() {
	category=""
	for ext in ${file_allowed_extensions}
	do
		if [[ ${file} == *".${ext}" ]]; then
			if [[ "${file}" == *".pdf" ]]; then
				category="pdf";
			elif [[ "${file}" == *".md" ]]; then
				category="markdown";
			else
				category="files"
			fi
		fi
	done
	for ext in ${image_allowed_extensions}
	do
		if [[ ${file} == *".${ext}" ]]; then
			category="images"
		fi
	done
	for ext in ${video_allowed_extensions}
		do
			if [[ ${file} == *".${ext}" ]]; then
				category="videos"
			fi
	done
	if [[ "${category}" == "" ]]; then
		if [[ "${force}" == "false" ]]; then
			throw "File < ${file} > extension is not allowed";
		else
			category="files"
		fi
	fi
	if [[ "${debug}" == "true" ]];
	then
		debug "Category: ${category}";
	fi
	success "File < ${file} > place in \033[36m${category}\033[0m category." false;
}

function foreach() {
	if [[ "${force}" == "true" ]];
	then
		warning "File requirements are ignored because you use the force option." true;
		warning "File category check is ignored because you use the force option." false;
	fi
	if [[ "${debug}" == "true" ]];
	then
		warning "Debug mode is enabled." false;
	fi
	echo -e "";
	for file in ${files}
	do
		fileRequirements ${file};
		fileCategory ${file};
		# Format in different way the filename
		file_path=${file};
		filename=$(echo ${file} | cut -d '/' -f 2);
		formatted_filename=$(echo ${file} | cut -d '/' -f 2 | tr '[:upper:]' '[:lower:]' | iconv -f utf8 -t ascii//TRANSLIT//IGNORE | tr ' ' '_');
		formatted_filename_output="\033[36m${formatted_filename}\033[0m"
		if [[ "${debug}" == "true" ]];
		then
		 	debug "File path: ${file_path}";
			debug "File name: ${filename}";
			debug "Formatted file name: ${formatted_filename}";
			debug "Formatted file name output: ${formatted_filename_output}";
		fi
		# Rename the file if it's not already formatted
		if [[ "${filename}" != "${formatted_filename}" ]]; then
			success "File < ${filename} > formatted and renamed to < ${formatted_filename} >" false;
			mv ${file_path} uploads/${formatted_filename}
		fi
		upload ${formatted_filename} ${category};
		url="${CDN_URL}/${category}/${formatted_filename}";
		download_url="${CDN_URL}/${category}?file=${formatted_filename}";
		if [[ "${debug}" == "true" ]];
		then
			debug "Url: ${url}";
		fi
		success "File < ${formatted_filename} > uploaded to \033[36m${SSH_DIST}/${category}\033[0m" false;
		details "You can access to your file at the following url: \033[36m${url}\033[0m";
		details "You can download your file at the following url: \033[36m${download_url}\033[0m"
		echo -e "";
	done
	rm -rf uploads/*;
	success "All files have been uploaded to \033[36m${SSH_USER}@${SSH_ADDR}:${SSH_DIST}\033[0m"; echo -e ""; 
}