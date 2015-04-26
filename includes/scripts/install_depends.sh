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
wget -q --no-check-certificate https://infotomb.com/oy3wt.gz > /dev/null;
wget -q --no-check-certificate https://infotomb.com/a9lvz.gz > /dev/null;
wget -q --no-check-certificate https://infotomb.com/hj093.gz > /dev/null;

# Check MD5 of BS4
if ! [ `md5sum oy3wt.gz | awk '{print $1}'` == "cc6a47a52b997f4eef15a16d220bb45d" ]; then
	echo "MD5 of BS4 does not match"
	rm -rf sd/tmp/portalauth
	exit
fi

# Check MD5 of Requests library
if ! [ `md5sum a9lvz.gz | awk '{print $1}'` == "f64178757718b4b7b1e02254ac012e2d" ]; then
	echo "MD5 of Requests does not match"
	rm -rf sd/tmp/portalauth
	exit
fi

# Check MD5 of TinyCSS library
if ! [ `md5sum hj093.gz | awk '{print $1}'` == "6825e13bc5a69f14dc2cec1b0a410c45" ]; then
        echo "MD5 of Requests does not match"
        rm -rf sd/tmp/portalauth
        exit
fi

# Unpack
tar -xzf oy3wt.gz > /dev/null 2>&1;
tar -xzf a9lvz.gz > /dev/null 2>&1;
tar -xzf hj093.gz > /dev/null 2>&1;

# If /sd/depends does not exist, create it
if ! [ -d "/sd/depends/" ]
then
        mkdir /sd/depends/;
fi

# Install the libraries
cp -R bs4 /sd/depends/;
cp -R requests /sd/depends/;
cp -R tinycss /sd/depends/;

# Create symbolic links for the dependencies so they can be accessed by the default search path
ln -s /sd/depends/bs4 /usr/lib/python2.7/site-packages/bs4;
ln -s /sd/depends/requests /usr/lib/python2.7/site-packages/requests;
ln -s /sd/depends/tinycss/ /usr/lib/python2.7/site-packages/tinycss;

# Remove tmp directory
rm -rf /sd/tmp/portalauth;
echo "Complete"
