#!/bin/bash

# This script is used to setup the environment for the CDN application.
# This program does not use any database, so you don't need to create one.
# Only one user will be created, and it will be stored in a file called "settings.yml".

# It will follow the steps below:
# 1. Ask you what environment you want to create.
# 2. Install composer dependencies
# 3. Ask you the credentials for your account.
#    3.1. Ask you the username.
#    3.2. Ask you the password (before storing it, it will be hashed).
# 5. Complete the file "config.php" with the environment you selected.
# 4. Complete the file "settings.yml" with the credentials you provided.

# This script does not use any option, so you can run it with:
# $ bash setup.sh

source utils/tools.sh;

function environment() {
	clear;
	echo -e "What environment do you want to create? [\e[0;33mDevelopment\e[0m]\n";
	echo "1. Development";
	echo -e "2. Production\n";
	read -p "> " option;
	case ${option} in
		1|Development|dev) env="development";;
		2|Production|prod) env="production";;
		*) environment;;
	esac
}

function configuration() {
	success "Dependencies installed successfully" true;
cat << EOF > config.php
<?php

return [
	'APP_ENV' => '${env}',
];
EOF
	success "Configuration file created successfully" true;
}

function dependencies() {
	rm -rf vendor > /dev/null 2>&1;
	if [ "${1}" == "--production" ]; then
		details "Selected environment: ${env}" true;
		composer install --no-dev --optimize-autoloader > /dev/null 2>&1;
  elif [ "${1}" == "--development" ]; then
		details "Selected environment: ${env}" true;
		composer install > /dev/null 2>&1;
  fi
}

function credentials() {
	echo -e "\nPlease provide the credentials for your account below\n";
	echo -e "Username: [\e[0;33mroot\e[0m] \c"; read -p "> " username;
	echo -e "\nPassword: [\e[0;33mpassword*123\e[0m] \c"; read -p "> " password;
	# Check if the username is empty or if the length is less than 3 characters.
	if [ -z "${username}" ]; then 
		username="root"; 
	elif [ ${#username} -lt 3 ]; then
		echo -e "\n\e[0;31mThe username must be at least 3 characters long.\e[0m";
		credentials;
	fi
	# Check if the password is empty or if the length is less than 8 characters.
	if [ -z "${password}" ]; then 
		password="password*123"; 
	elif [ ${#password} -lt 8 ]; then
		echo -e "\n\e[0;31mThe password must be at least 8 characters long.\e[0m";
		credentials;
	fi
	hashed_password=$(echo -n "${password}" | sha256sum | cut -d " " -f 1);
}

function settings() {
cat << EOF > settings.yml
user:
  username: "${username}"
  password: "${hashed_password}"
dashboard:
  title: ""
  description: ""
  image: ""
  owner:
    name: ""
    firstname: ""
    description: ""
EOF
success "Settings file created successfully" true;
success "Account created successfully" true;
details "Dont forget to change the settings in the file 'settings.yml'." true;
}

function setup() {
	clear;
	environment;
	configuration;
	dependencies "--${env}";
	credentials;
	settings;
	echo -e "";
}

setup;