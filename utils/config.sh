#!/bin/bash

if [ ! -f "cdn.sh" ]; then
	throw "Please run the script from the root directory." true;
fi

# Add alias to .bashrc
function configureBashrc() {
	warning "Configuring .bashrc..." true;
	grep -q "alias @cdn='bash $(pwd)/cdn.sh'" ~/.bashrc;
	if [ $? -eq 0 ]; then
		throw "Alias already present in .bashrc. Configuration aborted." true;
	fi
	echo -e "alias @cdn='bash $(pwd)/cdn.sh'" >> ~/.bashrc;
	if [ $? -ne 0 ]; then
		throw "Unable to configure .bashrc. Please check your permissions." true;
	else
		details "+ alias @cdn='bash /path/to/cdn.sh'" true;
		success "Configured successfully ✅" true;
		echo -e "";
	fi
}

configureBashrc;