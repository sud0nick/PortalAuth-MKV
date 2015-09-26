#!/bin/sh

if [ -e "/usr/lib/python2.7/site-packages/beautifulsoup4-4.4.0-py2.7.egg" ] && [ -e "/usr/lib/python2.7/site-packages/requests-2.5.1-py2.7.egg" ] && [ -e "/usr/lib/python2.7/site-packages/tinycss-0.3-py2.7.egg" ]
then
	echo "Installed";
else
	echo "Not Installed";
fi
