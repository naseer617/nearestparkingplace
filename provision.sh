#!/bin/bash

apache_config_file="/etc/apache2/envvars"

apache_sites_available_dir="/etc/apache2/sites-available/"

#apache config files
parkman_conf_file="parkman.local.conf"
parkman_api_conf_file="parkman.api.local.conf"

#php configs
php_config_file="/etc/php5/apache2/php.ini"
xdebug_config_file="/etc/php5/mods-available/xdebug.ini"

#mysql config
mysql_config_file="/etc/mysql/my.cnf"

#directories
default_apache_index="/var/www/parkman"
project_web_root="/var/www/parkman"

# This function is called at the very bottom of the file
main() {
	update_go
	network_go
	tools_go
	apache_go
	mysql_go
	php_go
	nodejs_go
	autoremove_go
	final_words
}

update_go() {
	# Update the server
	apt-get update
	# apt-get -y upgrade
}

autoremove_go() {
	apt-get -y autoremove
}

network_go() {
	IPADDR=$(/sbin/ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://')
	sed -i "s/^${IPADDR}.*//" /etc/hosts
	echo ${IPADDR} ubuntu.localhost >> /etc/hosts			# Just to quiet down some error messages
}

tools_go() {
	# Install basic tools
	apt-get -y install build-essential binutils-doc git subversion mercurial
}

build_apache_confs(){
	#creating parkman conf
	echo "Creating root conf"
	confFile=$apache_sites_available_dir$parkman_conf_file
	sed -i "s/^\(.*\)www-data/\1vagrant/g ${confFile}"
	chown -R vagrant:vagrant /var/log/apache2

	if [ ! -f "${confFile}" ]; then
		cat << EOF > ${confFile}
		<VirtualHost *:80>
		   ServerName parkman.local
		   ServerAlias parkman.local
		   DocumentRoot "/var/www/parkman/web"

		  <Directory /var/www/parkman/>
		     Options Indexes FollowSymLinks MultiViews
		     IndexOptions FancyIndexing FoldersFirst
		     AllowOverride All
		     Order allow,deny
		     Allow from all
		 </Directory>

		   ## Logging
		   ErrorLog "/var/log/apache2/local.parkman_error.log"
		   ServerSignature Off
		   CustomLog "/var/log/apache2/local.parkman_access.log" combined
		 </VirtualHost>
EOF
	fi

	a2ensite ${parkman_conf_file}


	#creating parkman.api conf
	#echo "Creating api.parkman conf"
	#_create_apache_log $parkman_api_conf_file '/var/www/parkman/target/api' 'api'
}

_create_apache_log(){
	name=$1
	source_dir=$2
	abbr=$3

	echo "Creating ${abbr}.parkman conf"
	ConfFile=$apache_sites_available_dir$name

	sed -i "s/^\(.*\)www-data/\1vagrant/g ${ConfFile}"  

	if [ ! -f "${ConfFile}" ]; then
		cat << EOF > ${ConfFile}
		 <VirtualHost *:80>
		   ServerName parkman.${abbr}.local
		   ServerAlias parkman.${abbr}.local
		   DocumentRoot "${source_dir}"

		  <Directory £{source_dir}>
		     Options Indexes FollowSymLinks MultiViews
		     IndexOptions FancyIndexing FoldersFirst
		     AllowOverride All
		     Order allow,deny
		     Allow from all
		 </Directory>

		   ## Logging
		   ErrorLog "/var/log/apache2/parkman.${abbr}.local_error.log"
		   ServerSignature Off
		   CustomLog "/var/log/apache2/parkman.${abbr}.local_access.log" combined
		 </VirtualHost>
EOF
	fi

	a2ensite ${name}
}

apache_go() {
	# Install Apache
	apt-get -y install apache2 apache2-utils 

	#For Admin Authentication
	apt-get -y install libapache2-mod-authnz-external libaprutil1-dbd-mysql

	build_apache_confs

	a2dissite 000-default

	a2enmod authz_default
	a2enmod authz_groupfile
	a2enmod autoindex
	a2enmod cgi
	a2enmod reqtimeout
	a2enmod rewrite
	a2enmod ssl

	#For Admin Authentication
	a2enmod dbd authn_dbd dbd_mysql authz_dbd authnz_external
	
	service apache2 reload
	update-rc.d apache2 enable
}

php_go() {
	#add-apt-repository ppa:ondrej/php5-5.6
	add-apt-repository ppa:ondrej/php
	apt-get update
	apt-get -y install php5.6 php5.6-curl php5.6-mysql php5.6-sqlite php5.6-xdebug libapache2-mod-php5.6 libmysqlclient-dev libssl-dev php5.6-xml php5.6-mbstring

	apt-get -y install zip unzip php5.6-zip

	sed -i "s/display_startup_errors = Off/display_startup_errors = On/g" ${php_config_file}
	sed -i "s/display_errors = Off/display_errors = On/g" ${php_config_file}

	if [ ! -f "{$xdebug_config_file}" ]; then
		cat << EOF > ${xdebug_config_file}
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_host=10.0.2.2
EOF
	fi

	service apache2 reload

	# Install latest version of Composer globally
	if [ ! -f "/usr/local/bin/composer" ]; then
		curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
	fi


	# Install PHP Unit 4.8 globally
	if [ ! -f "/usr/local/bin/phpunit" ]; then
		curl -O -L https://phar.phpunit.de/phpunit-old.phar
		chmod +x phpunit-old.phar
		mv phpunit-old.phar /usr/local/bin/phpunit
	fi
}

mysql_go() {
	# Install MySQL
	echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
	apt-get -y install mysql-client mysql-server

	sed -i "s/bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" ${mysql_config_file}

	# Allow root access from any host
	echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION" | mysql -u root --password=root
	echo "GRANT PROXY ON ''@'' TO 'root'@'%' WITH GRANT OPTION" | mysql -u root --password=root

	if [ -d "/vagrant/provision-sql" ]; then
		echo "Executing all SQL files in /vagrant/provision-sql folder ..."
		echo "-------------------------------------"
		for sql_file in /vagrant/provision-sql/*.sql
		do
			echo "EXECUTING $sql_file..."
	  		time mysql -u root --password=root < $sql_file
	  		echo "FINISHED $sql_file"
	  		echo ""
		done
	fi

	service mysql restart
	update-rc.d apache2 enable
}

nodejs_go(){
	curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash -
	apt-get install -y nodejs

	npm install -g bower
}

final_words(){
	echo " "
	echo " "
	echo "**********************"
	echo "****** Tadaaa!! ******"
	echo "**********************"
	echo " "
	echo " "
	echo "Further Instructions :"
	echo "1 - vagrant ssh"
	echo "2 - Once you are in your machine, cd to '/var/www/parkman/'"
	echo "3 - Run command 'sh install.sh'"
	echo "4 - Run command 'php yii migrate'"
	echo " "
	echo " "
	echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^"
	echo "PARKMAN   : http://parkman.local"
	echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^"
}

main
exit 0
