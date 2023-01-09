#!/bin/bash

function testSshCredentialsAreValid() {
	ssh -q -o BatchMode=yes -o ConnectTimeout=2 ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} exit
	if [[ $? -ne 0 ]]; then
		throw "❌ Ssh credentials are not valid" true;
		exit 1;
	else
		success "✅ Ssh credentials are valid" true;
	fi
}

function testSshDistantDirectoryIsValid() {
	d=$(echo ${SSH_DIST} | rev);
	if [[ ${d:0:1} == "/" ]]; then
		throw "❌ Ssh distant directory path is not valid (first char is /)" true;
		exit 1;
	fi
	ssh  -q -o BatchMode=yes -o ConnectTimeout=2 ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "ls ${SSH_DIST} > /dev/null 2>&1";
	if [[ $? -ne 0 ]]; then
		throw "❌ Ssh distant directory path is not valid" true;
		exit 1;
	else
		ssh  -q -o BatchMode=yes -o ConnectTimeout=2 ${SSH_USER}@${SSH_ADDR} -p ${SSH_PORT} "ls ${SSH_DIST}/pdf > /dev/null 2>&1";
		if [[ $? -ne 0 ]]; then
			throw "❌ Ssh distant directory path is not valid" true;
			exit 1;
		fi
		success "✅ Ssh distant directory path is valid";
	fi
}

function tests() {
	testSshCredentialsAreValid;
	testSshDistantDirectoryIsValid;
	echo -e "";
}

tests;