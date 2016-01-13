#!/usr/bin/env python
import sys
import os
import shlex
import subprocess
import tempfile

# NOTE: This script must be performed via root permissions. Also Sphinx is required

olddir = os.getcwd()
symfony_docs_repository = 'https://github.com/symfony/symfony-docs.git'
symfony_sphinx_ext_repository = 'https://github.com/fabpot/sphinx-php.git'

os.chdir(tempfile.gettempdir())

print('Cloning symfony-docs repo to {}...'.format(os.getcwd()))

subprocess.call(shlex.split('git clone {} symfony-docs'.format(
    symfony_docs_repository)))

print("Done!\nInstalling symfony sphinx extensions...")

subprocess.call(shlex.split('pip install git+{}'.format(
    symfony_sphinx_ext_repository)))

print("Done!\nBuilding the documentation...")

os.chdir('symfony-docs')
subprocess.call(shlex.split('make html'))

print("Documentation is done!")
sys.exit(0)
