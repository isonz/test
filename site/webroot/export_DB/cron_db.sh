#!/bin/bash

base_dir=/www/Bak/website/en.sanyatour.com;
bak_dir=$base_dir/data;
url=$base_dir/urls/en.sanyatour.com.url;
date_now=`date +%Y`-`date +%m`-`date +%d`;
filename=en.sanyatour.com.sql

php $base_dir/export_db_get.php > $url
wget -q -b -O $bak_dir/$filename -c -i $url

tar zcvfP $bak_dir/$date_now.tar.gz $bak_dir/*.sql
find $bak_dir -mtime +30 -name "*.*" -exec rm -rf {} \;

#scp -P 9022 $bak_dir/$date_now.tar.gz ison@pm.ptp.cn:/opt/dataBak

