source .env;

dir=$(dirname $0)
files=$(ls -1 uploads)

function uploads() {	
	ssh ${SSH_USER}@${SSH_ADDR} "sudo chown -R ${SSH_USER}:${SSH_USER} ${SSH_DIST}/${category}";
	rsync -azP -e 'ssh' ${dir}/uploads/${file} ${SSH_USER}@${SSH_ADDR}:${SSH_DIST}/${category}/;
	ssh ${SSH_USER}@${SSH_ADDR} "sudo chown -R www-data:www-data ${SSH_DIST}/${category}";
}

file_extension="pdf md txt"
image_extension="jpg jpeg png gif svg"
video_extension="mp4 webm"
	
for file in ${files}
do
	if [ -z ${file} ]; then
	        echo -e "Aucun fichier n'est présent dans le dossier \033[34muploads\033[0m."
	        exit 0
	fi
	formatted_file="\033[34m${file}\033[0m"
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
		uploads ${file} ${category} > /dev/null 2>&1;
		echo -e "Le fichier ${formatted_file} a été envoyé. Lien vers le cdn: \033[32m${CDN_URL}/${category}?file=${file}\033[0m";
	else
		echo -e "Le fichier ${formatted_file} ne correspond pas aux normes ou n'existe simplement pas..."
	fi
	mv ${dir}/uploads/${file} ${dir}/old/${file}
done
