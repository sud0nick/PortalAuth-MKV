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

# Install the libraries
cp -R beautifulsoup4-4.4.0 /sd/depends/bs4;
cp -R kennethreitz-requests-d2d576b /sd/depends/requests;
cp -R tinycss-0.3 /sd/depends/tinycss;
cp -R setuptools-18.2 /sd/depends/setuptools;

# Create symbolic links for the dependencies so they can be accessed by the default search path
ln -s /sd/depends/bs4 /usr/lib/python2.7/site-packages/bs4;
ln -s /sd/depends/requests /usr/lib/python2.7/site-packages/requests;
ln -s /sd/depends/tinycss/ /usr/lib/python2.7/site-packages/tinycss;

# Run the setup.py scripts
cd /sd/depends/setuptools/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/depends/bs4/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/depends/requests/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

cd /sd/depends/tinycss/;
python setup.py build > /dev/null;
python setup.py install > /dev/null;

# Remove tmp directory
rm -rf /sd/tmp/portalauth;
echo "Complete"
