#!/usr/bin/env bash
set -e

# this script must be called with root permissions
if [[ $(id -g rexx) != $2 || $(id -u rexx) != $1 ]]; then
    groupmod -g $2 rexx
    usermod -u $1 -g $2 rexx
fi;

cp /etc/profile /home/rexx/.profile
chown -R rexx:rexx /home/rexx
