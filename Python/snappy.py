#!/usr/bin/env python
import sys
import os
import shlex
import subprocess

SUBTITLE_EXT = '.srt'

pid = str(os.getpid())
pidfile = open("/tmp/snapy.py.pid", 'w')
pidfile.write(pid)

if len(sys.argv) > 1:
	filename = sys.argv[1]	
	subtitle = os.path.splitext(filename)[0] + SUBTITLE_EXT
	# subtitle exists?
	if os.path.exists(subtitle):		
		command = shlex.split('snappy -f -t {} {}'.format(subtitle, filename))
		subprocess.call(command)
	else:		
		subprocess.call(shlex.split('snappy -f {}'.format(filename)))
else:
	subprocess.call(["snappy"])
