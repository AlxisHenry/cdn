#!/bin/bash

function checkForUpdates() {
	details "Checking for updates..." true;
	echo -e "";
	git pull origin sync;
	if [ $? -ne 0 ]; then
		throw "Unable to check for updates. Please check your internet connection." true;
	else
		success "Updated successfully ✅" true;
		echo -e "";
	fi
}

checkForUpdates;