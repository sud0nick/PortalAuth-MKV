#!/bin/sh

if [ -d "/usr/lib/python2.7/site-packages/bs4" ] && [ -d "/usr/lib/python2.7/site-packages/requests" ] && [ -d "/usr/lib/python2.7/site-packages/tinycss" ]
then
	echo "Installed";
else
	echo "Not Installed";
fi
