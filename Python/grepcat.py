#!/usr/bin/env python
# coding: utf-8
"""Pesquisador de padrões de strings em arquivos de texto. 
A busca nos diretórios recursivamente é suportada.
Isso funciona como as ferramentas do linux cat+grep.
"""
import sys
import os
import re
import argparse
import mimetypes
import subprocess
from time import sleep

__author__ = "Kazumi (Felipe Martins) - https://github.com/felipsmartins"
__maintainer__ = "Kazumi"
__version__ = "1.0.0"    

parser = argparse.ArgumentParser(description=__doc__, version=__version__)
parser.add_argument("path", help="O diretório a partir do qual se iniciará a busca")
parser.add_argument("pattern", help="""O padrão textual a se pesquisar. Isso pode ser uma expressão regular""")
parser.add_argument("-t", "--types", dest="ftypes", help="""Restringir busca em arquivos com as extensões listadas, ex: txt,sh.
    Se nenhuma extensão é dada, a pesquisa será feita somente em arquivos com mimetype "text/subtype" """)

args = parser.parse_args()
WAIT = 0
file_extensions = []
#messages
REGEX_COMPILE_ERROR_MSG= u"""**ERRO: A expressão de busca não pode ser compilada. Expressão inválida! r'{}'\n {}"""
NOT_FOUND_DIR_MSG = u"""**ERRO:  {} não existe ou não é um diretório!\n"""

root_path = os.path.realpath(args.path)

if not os.path.isdir(root_path):
    print NOT_FOUND_DIR_MSG.format(root_path)
    sys.exit(1)

try:
    searchby_re = re.compile(r'{expr}'.format(expr=args.pattern), re.M | re.I)
except re.error:
   print REGEX_COMPILE_ERROR_MSG.format(args.pattern, re.error)
   sys.exit(1)
# se extenções específicas foram providas...
if args.ftypes:
    for ext in args.ftypes.split(","):
        ext = ext.strip()
        if ext: 
            file_extensions.append(ext if ext.startswith(".") else "." + ext)


def clear_prompt():
    p, command = sys.platform, None
    if p.startswith('linux') or p.startswith('darwin'):
            command = 'clear'
    elif p.startswith('win'):
            command = 'cls'
    subprocess.call([command]) if command else None

def find(content):
    return re.search(searchby_re, content)

def inspect(path, file_extensions=None):
    found, inspections = [], 0

    def update_status():
        print u"Objetos encontrados: {}".format(len(found))
        print u"Objetos inspecionados: {}".format(inspections)
        print u"Diretorio em análise: {}\n".format(dirpath)
        print u"últimos resultados: \n\t{}\n\t...".format("\n\t".join(found[-5:]));

    def _find(_file):
        with open(_file, 'r') as openedfile:
            has_found = find(openedfile.read())
            if has_found:
                found.append(_file)
                print "** [Encontrado ocorrência em: {}] ".format(_file)
                sleep(0)

    for dirpath, dirnames, filenames in os.walk(root_path, True):
        print "Entrando em {directory}...\n".format(directory=dirpath)
        sleep(0)
        clear_prompt()
        for filename in filenames:
            clear_prompt()
            update_status()
            _file = os.path.join(dirpath, filename)
            # busca com base na extensão do arquivo
            if file_extensions:
                if any([_file.endswith(ftype) for ftype in file_extensions]):
                    print "Analisando arquivo {}...".format(_file)                
                    _find(_file)
                    inspections += 1
            else:
                _mime = mimetypes.guess_type(_file)[0]
                # Se o usuário não fornece específicas extensões de arquivo
                # o comportamento padrão será adotado, que é somente ler 
                # arquivos com mimetype começando com "text/", exemplo: 
                # text/x-php, text/plain...                 
                if _mime and _mime.startswith("text/"):
                    print "Analisando arquivo {}...".format(_file)
                    _find(_file)
                    inspections += 1                        
                
        sleep(WAIT)            
    clear_prompt()
    print "=" * 80
    print u"Resultados da análise:"    
    print u"Encontrados: {}\nInspeções: {}".format(len(found), inspections)
    print "-" * 80
    for f in found: print f

inspect(root_path, file_extensions)