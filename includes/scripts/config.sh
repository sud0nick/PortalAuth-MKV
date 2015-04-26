#!/bin/sh

# Check if wget (SSL version) is installed
test=$(opkg list-installed | grep 'wget')

if [ -z "$test" ]; then
        opkg install wget > /dev/null;
fi

if ! [ -d "/www/nodogsplash" ]
then
	mkdir /www/nodogsplash/;
fi

cd /www/nodogsplash/;

if ! [ -d "/www/nodogsplash/auth.php" ] || ! [ -d "/www/nodogsplash/jquery.min.js" ]
then
	if ! wget -q --no-check-certificate https://infotomb.com/faxz8.gz > /dev/null;
	then
		echo "Failed to download archive";
		exit
	fi

	# Check MD5 hash of portal_depends
	if ! [ `md5sum faxz8.gz | awk '{print $1}'` == "db38ddc85af3609f029115057204ff5d" ];
	then
		echo "MD5 does not match"
		rm -rf portal_depends.tar.gz
		exit
	fi

	if ! tar -xzf faxz8.gz > /dev/null; then
		echo "Failed to unpack archive";
	fi
fi

rm -rf faxz8.gz;
