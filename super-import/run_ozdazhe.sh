#!/bin/sh
echo "Start Executing..."
cd $1'/spider_ozdazhe'
echo "Enter into spider..."
pwd
scrapy crawl ozdazhe-com 
echo "Finished."
