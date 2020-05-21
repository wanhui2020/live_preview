#!/bin/bash sh

root=$1

allFiles() {
    for file in "$1"/*; do
        if [ -d "$file" ]; then
            version=${file:2}
            deployFile="$root/$version/deploy/$2/deploy.$3.sh"
            if [ -e "$deployFile" ]; then
                 bash "$deployFile" "$version"
            fi
        fi
    done
}


# bash /www/wwwroot/dev.miqu.live.cqyzb.cn/latest/deploy/deploy.sh /www/wwwroot/dev.miqu.live.cqyzb.cn miqu dev

# $1=/www/wwwroot/preview.miqu.live.cqyzb.cn
# $2="miqu"
# $3="preview"

cd $root || exit

allFiles "." "$2" "$3"
