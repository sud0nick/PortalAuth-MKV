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
	if ! wget -q http://www.puffycode.com/download/PortalAuth/portal_depends.tar.gz > /dev/null;
	then
		echo "Failed to download archive";
		exit
	fi

	# Check MD5 hash of portal_depends
	if ! [ `md5sum portal_depends.tar.gz | awk '{print $1}'` == "db38ddc85af3609f029115057204ff5d" ];
	then
		echo "MD5 does not match"
		rm -rf portal_depends.tar.gz
		exit
	fi

	if ! tar -xzf portal_depends.tar.gz > /dev/null; then
		echo "Failed to unpack archive";
	fi
fi

rm -rf portal_depends.tar.gz;