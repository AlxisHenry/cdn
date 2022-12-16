source .env;

dir=$(dirname $0)
files=$(ls -1 uploads)

function uploads() {	
	ssh ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "sudo chown -R ${SSH_USER}:${SSH_USER} ${SSH_DIST}/${category}";
	rsync -azP -e "ssh -p ${SSH_PORT}" ${dir}/uploads/${filenameFormatted} ${SSH_USER}@${SSH_ADDR}:${SSH_DIST}/${category}/;
	ssh ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "sudo chown -R www-data:www-data ${SSH_DIST}/${category}";
}

file_extension="pdf md txt"
image_extension="jpg jpeg png gif svg"
video_extension="mp4 webm"
	
for file in ${files}
do
	filenameFormatted=$(echo ${file} | tr '[:upper:]' '[:lower:]' | iconv -f utf8 -t ascii//TRANSLIT//IGNORE)
	if [ -z ${file} ]; then
	        echo -e "Aucun fichier n'est présent dans le dossier \033[34muploads\033[0m."
	        exit 0
	fi
	filenameColored="\033[34m${filenameFormatted}\033[0m"
	if [ -f "${dir}/uploads/${file}" ]; then
		category=""
		for ext in ${file_extension}
		do
			if [[ ${file} == *".${ext}" ]]; then
				category="files"
			fi
		done
		for ext in ${image_extension}
		do
			if [[ ${file} == *".${ext}" ]]; then
				category="images"
			fi
		done
		for ext in ${video_extension}
		do
			if [[ ${file} == *".${ext}" ]]; then
				category="videos"
			fi
		done
		mv ${dir}/uploads/${file} ${dir}/uploads/${filenameFormatted} > /dev/null 2>&1;
		uploads ${filenameFormatted} ${category} > /dev/null 2>&1;
		echo -e "Le fichier ${filenameColored} a été envoyé :";
		echo -e "  - Lien vers le cdn: \033[32m${CDN_URL}/public/${category}?file=${filenameFormatted}\033[0m";
		echo -e "  - Lien vers le fichier: \033[32m${CDN_URL}/public/${category}/${filenameFormatted}\033[0m"
	else
		echo -e "Le fichier ${filenameColored} ne correspond pas aux normes ou n'est pas un fichier existant..."
	fi
	mv ${dir}/uploads/${file} ${dir}/archive/${file}
	find . -type f -name '* *' -delete
done
