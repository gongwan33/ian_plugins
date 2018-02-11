#!/bin/sh
echo "Start Executing..."
cd $1'/spider_ozdazhe'
echo "Enter into ozdazhe spider..."
pwd
scrapy crawl ozdazhe-com 
echo "Finished."

echo "Start Executing..."
cd $1'/deamoon_spider'
echo "Enter into dealmoon spider..."
pwd
scrapy crawl deamoon 
echo "Finished."

