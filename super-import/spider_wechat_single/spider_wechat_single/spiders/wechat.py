#coding:utf-8
import scrapy
import scrapy_splash
import json
import hashlib
import re
from scrapy.utils.response import open_in_browser

page_counter = 0;
MAX_PAGE = 150;

class JBScrapyWECHAT(scrapy.Spider):
    name = "wechat_single"

    headers = {
            "Accept": "*/*",
            "Accept-Encoding": "gzip,deflate",
            "Accept-Language": "en-US,en;q=0.8,zh-TW;q=0.6,zh;q=0.4",
            "Connection": "keep-alive",
            "Content-Type":" application/x-www-form-urlencoded; charset=UTF-8",
            "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36",
            "Referer": '' 
            }
    def __init__(self, url='', *args, **kwargs):
        self.url = url

    def start_requests(self):
        req = scrapy_splash.SplashRequest(self.url, self.parse_post, 
                args={
                # optional; parameters passed to Splash HTTP API
                'wait': 0.5,
                            
                # 'url' is prefilled from request url
                # 'http_method' is set to 'POST' for POST requests
                # 'body' is set to request body for POST requests
                },
                #endpoint='render.json', # optional; default is render.html
                #splash_url='<url>',     # optional; overrides SPLASH_URL
                #slot_policy=scrapy_splash.SlotPolicy.PER_DOMAIN,  # optional
        )

        yield req

    def parse_post(self, response):
        #print response.body
        #print response.xpath('//div[@id="js_content"]').extract();

        res_data = {
               'post_title': response.xpath('//h2[@class="rich_media_title"]/text()').extract_first(),
               'store': 'Manually copy',
               'category': '',
               'coupon_code': '',
               'expire_date': '',
               'img': '',
               'dst_url': ','.join(response.xpath('//div[@class="rich_media_content"]/a/@href').extract()),
               'tags': '',
               'post_data': '<div id="js_article" class="rich_media"><div class="rich_media_inner"><div id="page-content" class="rich_media_area_primary"><div id="img-content">' + response.xpath('//div[@class="rich_media_content "]').extract_first() + '</div></div></div></div>' + "\r\n\r\n" + ''.join(response.xpath('//style').extract()) + "\r\n\r\n" + ''.join(response.xpath('//script').extract()) + "\r\n\r\n",
        }

        return res_data

 
