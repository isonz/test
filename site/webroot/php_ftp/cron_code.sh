#!/bin/bash

base_dir=/www/Bak/website/en.sanyatour.com;
bak_dir=$base_dir/code;
date_now=`date +%Y`-`date +%m`-`date +%d`;

php $base_dir/ftp_file_get.php

tar zcvfP $bak_dir/$date_now.tar.gz $bak_dir/site/
find $bak_dir -mtime +70 -name "*.*" -exec rm -rf {} \;

#scp -P 9022 $bak_dir/$date_now.tar.gz ison@pm.ptp.cn:/opt/dataBak

