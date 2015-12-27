#!/usr/bin/env python
# coding: utf-8

import os
import sys
from urllib import urlencode
import urllib2 as net
import cookielib
from lxml import etree
import lxml.html

USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64; rv:41.0) Gecko/20100101 Firefox/41.0'
MAIN_URL = 'http://www.hyuugadownloads.com.br'

sep = lambda n=80: '-' * n
# global HTTP connector
cookiejar = cookielib.CookieJar()
cookiejar.clear_session_cookies()
opener = net.build_opener(net.HTTPCookieProcessor(cookiejar))
opener.addheaders = [('User-Agent', USER_AGENT), ('Referer', MAIN_URL), ]

def request(url, data=None, headers={}):
    data = urlencode(data) if data else data
    _request = net.Request(url, data, headers)
    response = opener.open(_request)
    # info/metadata
    return {'data': response.read(), 'headers': response.headers}

def to_absolute_url(path):
    return "{}/{}".format(MAIN_URL, path)

def extract_main_links(content, skip_count=None):
    """

    :rtype : list
    :param content:
    :param skip_count: Número links para pular
    :return: 
    """
    dom = lxml.html.document_fromstring(content)
    container = dom.xpath('//div[@id="inner-lista"]')[0]
    episodes = container.xpath('div[@class="episodio"]')
    episodes = episodes[skip_count:] if skip_count else episodes
    results = []

    # TODO: Use etree.XPathEvaluate() for performance
    for ep in episodes:
        episode_info = {}
        # title
        title = ep.xpath('div[@class="infos"]')[0].find('a')
        title = "{} - {}".format(title.get('title'), title.text_content().encode('utf8').strip())
        episode_info['title'] = title
        # mirrors
        episode_info['mirrors'] = []
        download_mirrors = ep.xpath('div[@class="caixa_mirror"]/table/tbody/tr')
        for dm in download_mirrors:
            links = dm.xpath('td/a')
            if len(links) > 0:
                link = links[0]
                episode_info['mirrors'].append({
                    "mirror": link.text_content(),
                    "link": to_absolute_url(link.get('href')),
                })
        results.append(episode_info)
    return results


def get_real_link_from_link_protector(link):
    """

    :param str link:
    :return:
    """
    content = request(link)
    dom = lxml.html.fromstring(content.get('data'))
    real_link = dom.xpath('//a[@id="link"]')
    if real_link:
        return real_link[0].get('href')
    return False


def display(results):
    # display
    for i in results:
        print i.get('title')
        for m in i.get('mirrors'):
            link = get_real_link_from_link_protector(m.get('link'))
            print "\t[{}] {}".format(m.get('mirror').upper(), link)
        print sep()


if '__main__' == __name__:
    anime_url = sys.argv[1]
    page = sys.argv[2]
    skip_links_per_page = None

    if len(sys.argv) > 3:
        skip_links_per_page = int(sys.argv[3])

    content = request(anime_url, {'p': page})

    if content:
        _results = extract_main_links(content.get('data'), skip_links_per_page)
        display(_results)

