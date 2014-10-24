# -*- coding: utf-8 -*-
import sys
import os
import re

def is_ip(ip):
    """Check whether the valid ip or not"""
    return True if re.match(r"^(\d{1,3}.){3}\d{1,3}$", ip) else False

def proxy_gen(proxyfile):
    """Returns a list of pairs (proxy, port) from opened proxy file"""

    proxies = []
    filehandler = open(proxyfile, 'r')
    lines = filehandler.readlines()

    for line in lines:
        parts = line.strip().split("\t")

        for key, part in enumerate(parts):
            if is_ip(part):
                proxy = part.strip()
                port = parts[key+1].strip()
                proxies.append((proxy, port))
    return proxies

# [(px, n[k+1].strip()) for n in [j.strip().split("\t") for j in ln] for k, px in enumerate(n) if is_ip(px)]
