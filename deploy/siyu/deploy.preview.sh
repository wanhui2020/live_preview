#!/bin/bash sh

cd /www/wwwroot/preview.siyu.live.cqyzb.cn/$1 || exit

git add . && git reset --hard HEAD && git pull preview $1

cp deploy/siyu/preview.env .env

if [ $1 != 'latest' ]; then
    exit
fi

php artisan migrate --force

php artisan schedule:run



# 创建每日队列日志文件
dir="/tmp/siyu_preview"
today=$(date "+%Y-%m-%d")
logFile="${dir}/queue_${today}.log"

if [ ! -d "${dir}" ]; then
    mkdir -p "${dir}"
fi

if [ ! -f "${logFile}" ]; then
    touch "${logFile}"
fi

php artisan queue:restart
nohup php artisan queue:work >"${logFile}" 2>&1 &

