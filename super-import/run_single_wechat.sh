#!/bin/sh
echo "Start Executing..."
cd $2'/spider_wechat_single'
echo "Enter into spider..."
pwd
scrapy crawl wechat_single -a url=$1  
echo "Finished."
