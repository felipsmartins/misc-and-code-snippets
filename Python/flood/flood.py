#-*- coding: utf-8 -*-

import sys
import random
import urllib
import urllib2
import telnetlib
from datetime import datetime

import utils
import proxy

LOG_FILE = "/tmp/invalid_proxies.log"
TELNET_MAX_TIMEOUT = 6
POST_URL = 'censored create post url...'
PROXY_FILE = 'proxies' # path for proxy file

_proxies = proxy.proxy_gen(PROXY_FILE)

def manage_proxy(proxies):
    def test(proxy, port):
        try:
            telnet = telnetlib.Telnet(proxy, port, TELNET_MAX_TIMEOUT);
            telnet.close()
            return True
        except: return False

    for proxy, port in proxies:
        if test(proxy, port):
            return ("%s:%s") % (proxy, port)
        print "Descartando %s:%s..." % (proxy, port)

def like_a_boss(fixedproxy=None, useproxy=False):
    topic_id = random.randint(1, 15)
    message = random.choice(utils.messages)
    message = message + " - " + random.choice(utils.message_autor)
    data = {
        "_method": "POST",
        "data[Reply][topic_id]": topic_id,
        "data[Reply][reply]": message,
    }

    try:
        request = urllib2.Request(POST_URL, urllib.urlencode(data), utils.headers)

        if useproxy:
            get_proxy = fixedproxy or manage_proxy(_proxies)
            request.set_proxy(get_proxy, "http")

        handler = urllib2.urlopen(request)
        print "Post_id: %s | msg: %s" % (topic_id, message)
        handler.close()
    except:
        print "\a"
        print "Erro: Servidor parece está indisponível"

for i in range(1, int(sys.argv[1])):
    like_a_boss()
    print str(i) + " ="*50
