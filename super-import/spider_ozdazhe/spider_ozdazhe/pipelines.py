# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
from twisted.enterprise import adbapi
from datetime import datetime
import MySQLdb
import MySQLdb.cursors
import sys

class SpiderOzdazhePipeline(object):
    def __init__(self, conn):
        self.conn = conn
        self.cursor = self.conn.cursor()
        self.counter = 0

    @classmethod
    def from_settings(cls, settings):
        host = settings['MYSQL_HOST']
        db = settings['MYSQL_DBNAME']
        user = settings['MYSQL_USER']
        passwd = settings['MYSQL_PASSWD']

        now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        conn = MySQLdb.connect(host, user, passwd, db, charset="utf8", use_unicode=True)
        cursor = conn.cursor()

        print("Cleaning old data...")
        cursor.execute("""TRUNCATE TABLE ozdazhe_data""")
        cursor.execute("""TRUNCATE TABLE spider_status""")
        conn.commit()
        print("Cleaning completed.")

        print("Writing status data..." + now)
        cursor.execute("""INSERT INTO spider_status (status, item_num, start_time) VALUES (%s, %s, %s)""", ("running", 0, now))
        conn.commit()
        print("Writing finished.")

        return cls(conn)

    def close_spider(self, spider):
        print("Exiting...")

        if self.conn:
            status = "stop"
            sql = "UPDATE spider_status SET status=\"%s\"" % status
            self.cursor.execute(sql)
            self.conn.commit()

        print("Exit!")


    def process_item(self, item, spider):
        print('process_item start=================>')
        try:
            self.cursor.execute("""INSERT INTO ozdazhe_data (title, store, category, coupon_code, expire_date, img, dst_url, tags, post_data) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)""", (
                item['post_title'].encode('utf-8'), 
                item['store'].encode('utf-8'), 
                item['category'].encode('utf-8'), 
                item['coupon_code'].encode('utf-8'), 
                item['expire_date'].encode('utf-8'), 
                item['img'].encode('utf-8'), 
                item['dst_url'].encode('utf-8'), 
                item['tags'].encode('utf-8'), 
                item['post_data'].encode('utf-8')))
            self.counter += 1
            self.cursor.execute("""UPDATE spider_status SET status=%s, item_num=%s""", ("running", self.counter))
            self.conn.commit()

        except MySQLdb.Error, e:
            print "Error %d: %s" % (e.args[0], e.args[1])

        print('process_item end<=================')

        return item
