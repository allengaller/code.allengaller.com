#!/bin/bash
#
# Installs, debugs, or removes a LAMP server stack.
# Will also help configure the server.
#
# By John Shanahan
# 2012
#################################################
#TODO
# Change from apt-get to downloading source and making
# everything manually, to make portable.
#################################################
#TODO
# Change the end of Install from giving tips on how to
# configure PHP, Apache, and MySQL to asking if 
# they just want us to do it for them.
#################################################


#################################################
# Header/name
#################################################
printf "\n####################\n"
printf "#  Lamp-install.sh #\n# By John Shanahan #\n"
printf "####################\n\n"

######################
#  Set no casematch  #
######################
shopt -s nocasematch

#################################################
# Requires run as root (or sudo)
#################################################
printf "Checking UID...\n"
if (( EUID != 0 )); then
	printf "You must be root to do this, Please use 'sudo ./lamp-install'.\n" 1>&2
	exit 10
else
	printf "User is root ...\n"
fi

#################################################
# Installing LAMP or Debugging?
#################################################
printf "\nAre you installing, debugging, or removing an existing LAMP server stack?\n"
printf "(I)nstalling LAMP stack.\n"
printf "(D)ebugging a current installation.\n"
printf "(R)emoving a current LAMP installation.\n"
printf "(Q)uit.\n"
printf "> "
	read install_debug_rm

#################################################
# Installing LAMP server stack
#################################################
if [[ ( "$install_debug_rm" = i ) ]]
then
	# Update and Upgrade
	printf "\nUpdating ...\n"
	apt-get -qqq update

	##############################
	#  Install required packages #
	##############################
	apt-get -qqq install apache2 php5 libapache2-mod-php5 libapache2-mod-auth-mysql mysql-server php5-mysql phpmyadmin

	#############################
	#     Configure packages    #
	#############################

	printf "\nWould you like to add 'ServerName localhost' to /etc/apache2/conf.d/fqdn? (y/n)\n"
	printf "If this is a development box or you're not sure type y to be safe.\n"
	printf "> "
	read serv_local

	if [[ ( "$serv_local" = y ) ]]
	then
		echo "ServerName localhost" >> /etc/apache2/conf.d/fqdn
	else
		printf "Nothing was added to /etc/apache2/conf.d/fqdn ...\n"
	fi

	######################
	#   Restart Apache   #
	######################
	printf "\nRestarting apache2 ...\n"
	/etc/init.d/apache2 restart

	########################
	#   How to configure   #
	########################
	printf "\n\nTo configure MySQL type $ mysql -u root\n\n"
	printf "For other computers on your network to see the server\n"
	printf "edit the 'bind-address = 127.0.0.1' line in\n"
	printf "your /etc/mysql/my.cnf to your IP address.\n\n"
	printf "To get PHP to work with MySQL you need to uncomment the\n"
	printf "line in /etc/php5/apache2/php.ini that says ';extension=mysql.so'\n\n"

	# Unset casematch
	shopt -u nocasematch
	exit 0
fi

#################################################
# Debugging options
#################################################
if [[ ( "$install_debug_rm" = d ) ]]
then
	printf "Debugging ...\n"
	# Unset casematch
	shopt -u nocasematch
	exit 0
fi


#################################################
# Removing LAMP stack
#################################################
if [[ ( "$install_debug_rm" = r ) ]]
then
	printf "Removing LAMP ...\n"

	apt-get purge -qqq apache2 php5 libapache2-mod-php5 mysql-server php5-mysql libapace2-mod-auth-mysql phpmyadmin

	printf "LAMP stack removed ...\n"
	# Unset casematch
	shopt -u nocasematch
	exit 0
fi

if [[ ( "$install_debug_rm" = q ) ]]
then
	printf "Quitting ...\n"
	# Unset casematch
	shopt -u nocasematch
	exit 0
fi

#Another shell:
#!/bin/bash

#################################################################
# Script to install LAMP Server, that consist of Apache server, #
#    MySQL server and phpmyadmin.                               #
#                                                               #
#                                                               #
#  run in terminal, use ./lamp.sh                               #
#                                                               #
# Made By : Sandeep Kaur                                        #
# http://sandymadaan.wordpress.com/                             #
#                                                               #
#                                                               #
# created : 01-11-2012                                          #
#                                                               #
#                                                               #
#################################################################

#sudo apt-get install apache2                               # to install apache server
#sudo apt-get install php5 libapache2-mod-php5              # for integrating apache and php
#sudo apt-get install mysql-server                          # to install mysql server
#sudo apt-get install phpmyadmin                            # to install phpmyadmin
#sudo apt-get install libapache2-mod-auth-mysql php5-mysql  # for integrating apache, php and mysql
#sudo /etc/init.d/apache2 restart                           # to restart apache
#echo "Congratulations!!! LAMP is successfully installed. "

#Yet another shell:
#!/usr/bin/bash

# Update Ubuntu environment
# update() {
#   sudo apt-get update
#   sudo apt-get upgrade
#   sudo apt-get install sh  
# }

# # LAMP installation; this includes some PHP modules and PHPMyAdmin as well
# lamp() {
#   sudo apt-get install apache2 pear myql-server mysql-client memcached php5 phpmyadmin libapache2-mod-php5 php5-memcache php5-memcached php5-mysql php5-common php5-cli php5-dev php5-curl php5-imagick php5-imap php5-intl
#   ln -s /usr/share/phpmyadmin/ /var/www/phpmyadmin
#   sudo /etc/init.d/apache2 restart
# }

# zend() {
#   sudo add-apt-repository ppa:zend-framework/ppa
#   sudo apt-get update
#   sudo apt-get install zend-framework
# }

# eclipse() {
#   sudo apt-get install eclipse
# }

# phpunit() {
#   sudo apt-get install phpunit
#   sudo pear upgrade pear
#   sudo pear channel-discover pear.phpunit.de
#   sudo pear channel-discover components.ez.no
#   sudo pear channel-discover pear.symfony-project.com
#   sudo pear install --alldeps phpunit/PHPUnit
  
# #Next steps to fix issues (Upgrading to 3.5)
#   sudo pear install -f phpunit/DbUnit
#   sudo pear install -f phpunit/PHPUnit_MockObject
#   sudo pear install -f phpunit/PHPUnit_Selenium
# }

# # Success!
# exit 0;