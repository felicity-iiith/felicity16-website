#!/bin/bash
locales=("hi_IN" "te_IN" "gu_IN")

if [ $# -lt 3 ]
then
    echo "Usage: $0 <user@remote> <port> <base_path>" >&2
    exit 1
fi

remote=$1
port=$2
remote_path=$3

for locale in ${locales[@]}
do
    echo "Compiling $locale"
    msgfmt src/locale/${locale}/LC_MESSAGES/messages.po -o src/locale/${locale}/LC_MESSAGES/messages.mo
done

scp -P $port -r src/locale/* $remote:$remote_path/locale/
ssh -p $port $remote service apache2 restart
