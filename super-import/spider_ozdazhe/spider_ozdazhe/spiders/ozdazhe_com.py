import scrapy
import hashlib
from scrapy.utils.response import open_in_browser

page_counter = 0;
MAX_PAGE = 150;

class JBScrapyOZDAZHE(scrapy.Spider):
    name = "ozdazhe-com"
    urls = [
            'http://www.ozdazhe.com/show.php',
    ]

    headers = {
            "Accept": "*/*",
            "Accept-Encoding": "gzip,deflate",
            "Accept-Language": "en-US,en;q=0.8,zh-TW;q=0.6,zh;q=0.4",
            "Connection": "keep-alive",
            "Content-Type":" application/x-www-form-urlencoded; charset=UTF-8",
            "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36",
            "Referer": urls[0] 
            }

    def start_requests(self):
        return [scrapy.Request(url = self.urls[0],
                               callback = self.parse_login)]

    def parse_login(self, response): 
        md5_pwd = hashlib.md5()
        md5_pwd.update('123456z')
        md5_pwd_str = md5_pwd.hexdigest()
        #print(md5_pwd_str)

        return [scrapy.FormRequest.from_response(response, 
                                        headers = self.headers,
                                        formdata = {
                                                        'fastloginfield': 'username',
                                                        'username': 'jb_bot',
                                                        'password': md5_pwd_str,
                                                        'quickforward': 'yes',
                                                        'handlekey':'ls',
                                                    },
                                        callback = self.parse_afterlogin)]


    def parse_afterlogin(self, response):
        global MAX_PAGE

        if page_counter >= MAX_PAGE:
            print "Max number meet exit=======================>>>>>>>>>>>>>"
            return

        #print(response.xpath('//div[@id="hd"]').extract_first())
        if 'id="g_upmine"' in response.xpath('//div[@id="hd"]').extract_first():
            self.log("---------------------Login successed!-----------------------")
        else:
            self.log("----------------------Login failed!!------------------------")

        item_urls = response.xpath('//div[@class="bbda cl"]/a/@href').extract()
        for url in item_urls:
            if page_counter >= MAX_PAGE:
                print "Max number meet exit=======================>>>>>>>>>>>>>"
                return

            yield response.follow(url, callback=self.subpage_parse)

        next_url = response.xpath('//a[@class="nxt"]/@href').extract_first()
        if next_url is not None:
            yield response.follow(next_url, callback=self.parse_afterlogin)

    def subpage_parse(self, response):
        global page_counter
        page_counter += 1

        if page_counter >= MAX_PAGE:
            print "Max number meet exit=======================>>>>>>>>>>>>>"
            return

        data_table_rows = response.xpath('//div[@class="dongzhe"]/dl/dd/table/tbody/tr')

        res_data = {
               'post_title': response.xpath('//span[@id="thread_subject"]/text()').extract_first(),
               'store': data_table_rows[0].xpath('.//td/text()').extract_first(),
               'category': data_table_rows[3].xpath('.//td/text()').extract_first(),
               'coupon_code': data_table_rows[4].xpath('.//td/text()').extract_first(),
               'expire_date': data_table_rows[5].xpath('.//td/text()').extract_first(),
               'img': response.urljoin(data_table_rows[6].xpath('.//td/img/@src').extract_first()),
               'dst_url': ','.join(response.xpath('//td[re:test(@id, "postmessage_*")]/node()[self::a]/text()').extract()),
               'tags': ','.join(response.xpath('//div[@class="ptg mbm mtn"]/a/text()').extract()),
               'post_data': ' '.join(response.xpath('//td[re:test(@id, "postmessage_*")]/node()[not(self::div[@class="a_pr" or @class="attach_nopermission attach_tips"]) and not(self::img)]').extract()),
        }

        return res_data
        #yield res_data
