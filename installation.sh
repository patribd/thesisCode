#!/bin/bash

if [ $# -eq 0 ]; then
	echo "Introduce an IP address to send all the installation information."
	exit 1
fi

argument="$1"

# Send all the installation data
scp -rp ~/Desktop/thesisCode/odroidConfiguration odroid@"$argument":/home/odroid/   


