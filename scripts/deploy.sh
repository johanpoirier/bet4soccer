#!/bin/sh

projectDir="$(dirname $0)/../"

target=${@: -1}
targetPath=~/$target

if [ -e $targetPath ]
then
    version=`date '+%Y%m%d%H%M%S'`
    mkdir -p "$targetPath/versions/$version/web/"
    rm -f "$targetPath/current"
    ln -s "$targetPath/versions/$version/" "$targetPath/current"

    cd "$projectDir"
    cp -r app/* "$targetPath/current/web/"
    mkdir "$targetPath/current/logs"
    chmod 775 "$targetPath/current/logs"
    cp "$targetPath/conf/config.inc.php" "$targetPath/current/web/lib/config.inc.php"
    cp "$targetPath/conf/params.inc" "$targetPath/current/web/lib/protect/params.inc"
    chgrp -R www-data "$targetPath/current/"
    cd --

    echo "Version $version created"
else
    echo "$target does not exist"
fi
