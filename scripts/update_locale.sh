#!/bin/bash
locales=("hi_IN" "te_IN" "gu_IN" "mr_IN")

echo "Getting locale_dump from https://felicity.iiit.ac.in"
status=$(curl -# --write-out "%{http_code}" "https://felicity.iiit.ac.in/jugaad/locale_dump/" -o "src/locale/locale_dump.php")
if [ $status -ne 200 ]
then
    echo "Could not get file from server"
    exit 1
fi

dos2unix src/locale/locale_dump.php

echo -n "Generating .pot file"
find src -iname "*.php" | xargs xgettext -L PHP -k__ -k_ -kgettext --from-code utf-8 -o src/locale/messages.pot
echo " ..... done."
for locale in ${locales[@]}
do
    echo "Merging $locale"
    msgmerge --backup=none -U src/locale/${locale}/LC_MESSAGES/messages.po src/locale/messages.pot
done

echo "Cleaning up"
rm src/locale/locale_dump.php
