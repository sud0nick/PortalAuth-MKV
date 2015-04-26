#!/bin/sh

tar -czf /pineapple/components/infusions/portalauth/downloads/$1.tar.gz -C /pineapple/components/infusions/portalauth/includes/scripts/injects/ $1/
echo "Complete"
