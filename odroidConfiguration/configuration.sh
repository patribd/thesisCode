#!/bin/bash

for i in {0..9};
	do device="/dev/ttyACM$i"
	if [ -e "$device" ]; then
		stty -F "$device" 115200
	fi
done

sudo apt update
sudo apt install python3

sudo apt update
sudo apt install odroid-wiringpi
sudo apt install libwiringpi-dev

sudo apt install software-properties-common
sudo add-apt-repository ppa:hardkernel/ppa
sudo apt update
sudo apt install odroid-wiring-python

sudo apt install python python-pip
sudo odroid-wiringpi-python --install

sudo apt update
sudo apt install openssl

sudo apt update
sudo apt install curl

pip install requests
pip install wiringpi
pip install netifaces

sudo apt update
sudo apt install openssh-server
sudo apt update
sudo apt install openssh-client
sudo systemctl start ssh
sudo systemctl enable ssh
#sudo nano /etc/ssh/sshd_config       Modifiy this file so it has PasswordAuthentication yes
sudo systemctl restart ssh
