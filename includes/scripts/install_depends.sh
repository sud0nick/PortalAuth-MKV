#!/bin/sh

# Update the opkg list
opkg update > /dev/null;

# Check if wget (SSL version) is installed
test=$(opkg list-installed | grep 'wget')

if [ -z "$test" ]; then
	opkg install wget > /dev/null;
fi

# Make a temporary directory for downloading depends
if ! [ -d "/sd/tmp/" ]; then
	mkdir /sd/tmp
fi

# Remove any old copies of PortalAuth tmp files from previous attempts
rm -rf /sd/tmp/portalauth
mkdir /sd/tmp/portalauth;
cd /sd/tmp/portalauth;

# Download the depends
wget -q http://www.puffycode.com/download/PortalAuth/beautifulsoup4-4.4.0.tar.gz > /dev/null;
wget -q http://www.puffycode.com/download/PortalAuth/requests-v2.5.1.tar.gz > /dev/null;
wget -q http://www.puffycode.com/download/PortalAuth/tinycss-0.3.tar.gz > /dev/null;
wget -q http://www.puffycode.com/download/PortalAuth/setuptools-18.2.tar.gz > /dev/null;

# Check MD5 of BS4
if ! [ `md5sum beautifulsoup4-4.4.0.tar.gz | awk '{print $1}'` == "63d1f33e6524f408cb6efbc5da1ae8a5" ]; then
	echo "MD5 of BS4 does not match"
	rm -rf /sd/tmp/portalauth
	exit
fi

# Check MD5 of Requests library
if ! [ `md5sum requests-v2.5.1.tar.gz | awk '{print $1}'` == "3c5bd282c56353d56ac39b6dee12560f" ]; then
	echo "MD5 of Requests does not match"
	rm -rf /sd/tmp/portalauth
	exit
fi

# Check MD5 of TinyCSS library
if ! [ `md5sum tinycss-0.3.tar.gz | awk '{print $1}'` == "13999e54453d4fbc9d1539f4b95d235e" ]; then
        echo "MD5 of TinyCSS does not match"
        rm -rf /sd/tmp/portalauth
        exit
fi

# Check MD5 of SetupTools
if ! [ `md5sum setuptools-18.2.tar.gz | awk '{print $1}'` == "52b4e48939ef311d7204f8fe940764f4" ]; then
        echo "MD5 of SetupTools does not match"
        rm -rf /sd/tmp/portalauth
        exit
fi

# Unpack
tar -xzf beautifulsoup4-4.4.0.tar.gz > /dev/null 2>&1;
tar -xzf requests-v2.5.1.tar.gz > /dev/null 2>&1;
tar -xzf tinycss-0.3.tar.gz > /dev/null 2>&1;
tar -xzf setuptools-18.2.tar.gz > /dev/null 2>&1;

# If /sd/depends does not exist, create it
if ! [ -d "/sd/depends/" ]
then
        mkdir /sd/depends/;
fi

# Move site-packages to /sd/depends/
if ! [ -d "/sd/depends/site-packages/" ]; then
	if ! [ -d "/usr/lib/python2.7/site-packages/" ]; then

		# This is here in case a symbolic link still exists
		# from a previous installation
		rm -rf "/usr/lib/python2.7/site-packages/";
		mkdir "/usr/lib/python2.7/site-packages/";
	fi

	mv /usr/lib/python2.7/site-packages /sd/depends/site-packages
	ln -s /sd/depends/site-packages/ /usr/lib/python2.7/site-packages
fi

# Run the setup.py scripts
cd /sd/tmp/portalauth/setuptools-18.2/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/tmp/portalauth/beautifulsoup4-4.4.0/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/tmp/portalauth/kennethreitz-requests-d2d576b/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/tmp/portalauth/tinycss-0.3/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

# Remove tmp directory
rm -rf /sd/tmp/portalauth;
echo "Complete"
