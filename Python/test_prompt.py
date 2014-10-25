#coding: utf-8

import os, sys
import getpass

h = """
 +-----------------------------------------------------------------------------+
 |                                CONFIGURAÇÃO                                 |
 +-----------------------------------------------------------------------------+
 """

print h

email = raw_input("Passo 1) Informe sua conta do gmail incluindo o @gmail.com\n")
email = email.strip()


email_passswd = getpass.getpass("Passo 2) Informe sua senha do gmail\n")
email_passswd = email_passswd.strip()
