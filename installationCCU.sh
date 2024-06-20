#!/bin/bash

sudo apt update
sudo apt install apache2
sudo systemctl start apache2
sudo systemctl enable apache2
sudo ufw allow 'apache2'

sudo apt update
sudo apt install openssl

sudo apt update
sudo apt install mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql
sudo mysql_secure_installation

sudo apt update
sudo apt install curl

sudo apt update
sudo apt install openssh-server
sudo apt update
sudo apt install openssh-client
sudo systemctl start ssh
sudo systemctl enable ssh
#sudo nano /etc/ssh/sshd_config       Modifiy this file so it has PasswordAuthentication yes
sudo systemctl restart ssh

