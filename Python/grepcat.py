#!/usr/bin/env python
# coding: utf-8
import sys
import os
import re
import subprocess
from time import sleep

WAIT = 0
root_path = None
filetypes = []
#messages
REGEX_COMPILE_ERROR_MSG= u"""**ERRO: A expressão de busca não pode ser compilada. Expressão inválida! r'{}'\n {}"""
ARGUMENTS_NOT_SUPPLIED_MSG = u"""**ERRO: Argumentos requeridos não foram fornecidos:
    Uso: \n     {} './diretorio/raiz/para/busca' 'RegEx' 'php,txt,py'"""
NOT_FOUND_DIR_MSG = u"""**ERRO:  {} não existe ou não é um diretório!\n"""

if len(sys.argv) < 4:
    print ARGUMENTS_NOT_SUPPLIED_MSG.format(sys.argv[0])
    sys.exit(1)
root_path = os.path.realpath(sys.argv[1])
if not os.path.isdir(root_path):
    print NOT_FOUND_DIR_MSG.format(root_path)
    sys.exit(1)
try:
    searchby_re = re.compile(r'{expr}'.format(expr=sys.argv[2]), re.M | re.I)
except re.error:
   print REGEX_COMPILE_ERROR_MSG.format(sys.argv[2], re.error)
   sys.exit(1)

for t in  sys.argv[3].split(","):
    if t: filetypes.append(t if t.startswith(".") else "." + t)

#print "DEBUG:\n{}\n{}\n{}".format(filetypes, root_path, searchby); sys.exit(0)

def clear_prompt():
    p, command = sys.platform, None
    if p.startswith('linux') or p.startswith('darwin'):
            command = 'clear'
    elif p.startswith('win'):
            command = 'cls'
    subprocess.call([command]) if command else None

def find(content):
    return re.search(searchby_re, content)

def inspect(path, filetypes=None):
    found, inspections = [], 0

    def update_status():
        print u"Objetos encontrados: {}".format(len(found))
        print u"Objetos inspecionados: {}".format(inspections)
        print u"Diretorio em análise: {}\n".format(dirpath)
        print u"últimos resultados: \n\t{}\n\t...".format("\n\t".join(found[-5:]));

    for dirpath, dirnames, filenames in os.walk(root_path, True):
        print "Entrando em {directory}...\n".format(directory=dirpath)
        sleep(0)
        clear_prompt()
        for filename in filenames:
            clear_prompt()
            update_status()
            _file = os.path.join(dirpath, filename)
            if filetypes and any([_file.endswith(ftype) for ftype in filetypes]):
                print "Analisando arquivo {}...".format(_file)
                with open(_file, 'r') as openedfile:
                    has_found = find(openedfile.read())
                    if has_found:
                        found.append(_file)
                        print "** [Encontrado ocorrência em: {}] ".format(_file)
                        sleep(0)
                inspections += 1
            else:
                #print "{} parece não ser um arquivo válido\n {}".format(_file, filetypes); sys.exit()
                #TODO: Analizar qualquer arquivo,
                #se não é provida uma lista de formatos
                pass
        sleep(WAIT)
            
    clear_prompt()
    print "*"*80
    print u"Resultados da análise:"
    print u"Encontrados: {}\nInspeções: {}".format(len(found), inspections)
    for f in found: print f

inspect(root_path, filetypes)
