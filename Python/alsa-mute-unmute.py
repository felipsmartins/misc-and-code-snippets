#!/usr/bin/env python
#coding: utf-8

import sys
import subprocess

mute_command   = 'amixer sset Master mute'
unmute_command = 'amixer sset Master unmute'

def sys_message(msg):
    subprocess.call(['notify-send', '-t', '1000', '{}'.format(msg)])
    
process = subprocess.Popen(['amixer sget Master'], shell=True, stdout=subprocess.PIPE)
output= process.communicate()[0].split("\n")
is_mute = False

for line in output:
    data = line.lower()    
    if ('front left' in data) and '[off]' in data:
        is_mute = True        
if is_mute:
    p = subprocess.Popen(['amixer sset Master unmute'], shell=True, stdout=subprocess.PIPE)  
    sys_message("Mute foi desativado") #mute was disabled
else:    
    p = subprocess.Popen(['amixer sset Master mute'], shell=True, stdout=subprocess.PIPE) 
    sys_message("Mute foi ativado") #mute was enabled
    
sys.exit(0)