#!/usr/bin/env python

import sys
import os
import codecs
import subprocess
import shlex
import glob

ICONV_PATH = '/usr/bin/iconv'
ICONV_AVAILABLE = True if os.path.isfile(ICONV_PATH) else False
extensions = ['text', 'txt', 'sql']

if len(sys.argv) < 2:
    print "Missing target dir"
    sys.exit(1)
    
target_dir = os.path.abspath(sys.argv[1])

if os.path.exists(target_dir):
    pattern = "{}{}*[\.{}]".format(target_dir, os.sep, "\.".join(extensions))
    files = glob.glob(pattern)    
    if ICONV_AVAILABLE and files:
        for f in files:
            print "convertendo {}...".format(f),
            command = "{} -f 8859_1 -t UTF8 {} -o {}".format(ICONV_PATH, f, f)
            subprocess.call(shlex.split(command),  shell=False)
            print "ok!"
            
        
    
    