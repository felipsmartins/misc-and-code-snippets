#coding: utf-8

__author__ = 'felipe.martins.dev@gmail.com'

# -----------------------------------------------------------------------------+
#                 SEND MAIL                                                    |
# -----------------------------------------------------------------------------+

import os
import sys
import getpass
import time
import subprocess
import smtplib
import xml.etree.ElementTree
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText


def get_location():
    HAS_FOUND = False
    location = None
    while(not HAS_FOUND):
        location = raw_input("\nInforme a localidade correta do arquivo config.xml:\n");
        if os.path.isfile(location.strip()):
            HAS_FOUND = True
        else:
          print """**O Arquivo "{}" não é válido ou não existe!""".format(location)
    return location

def clear_prompt():
    p, command = sys.platform, None
    if p.startswith('linux') or p.startswith('darwin'):
        os.system("clear")   #subprocess.call([command])
    elif p.startswith('win'):
        os.system("cls")


POLICY_MESSAGE_1 =  """

+------------------------------------------------------------------------------+
|                        E S T E J A    C I E N T E                            |
|                                                                              |
|                                                                              |
| A política de segurança dos servidores Google permite envio máximo de 500    |
| emails por dia. Saiba mais:                                                  |
| https://support.google.com/mail/answer/22839?hl=pt-BR                        |
|                                                                              |
+------------------------------------------------------------------------------+

"""

POLICY_MESSAGE_2 =  """

+------------------------------------------------------------------------------+
|                           A T E N Ç Ã O !                                    |
|                                                                              |
|                    * Você não pode enviar tantos e-mails. *                  |
|                                                                              |
| A política de segurança dos servidores Google permite envio máximo de 500    |
| emails por dia. Saiba mais:                                                  |
| https://support.google.com/mail/answer/22839?hl=pt-BR                        |
|                                                                              |
+------------------------------------------------------------------------------+

"""


PROMPT_HEAD = h = """
 +-----------------------------------------------------------------------------+
 |            CONFIGURAÇÃO  DE ENVIO DE MENSAGENS                              |
 +-----------------------------------------------------------------------------+
 """

print POLICY_MESSAGE_1

go = raw_input("""Presione ENTER para prosseguir ou "n" para sair\n""")

if (go.lower() == 'n'):
    sys.exit('saindo...')

clear_prompt()
print PROMPT_HEAD

email = raw_input("Passo 1) Informe sua conta do gmail incluindo o @gmail.com\n")
email = email.strip()

email_passswd = getpass.getpass("\nPasso 2) Informe sua senha do gmail\n")
email_passswd = email_passswd.strip()

#obtendo xml...
#detecção automática:
default_filepath = os.path.join(os.path.dirname(__file__), "config.xml")

if os.path.isfile(default_filepath):
    print "config.xml foi detectado automaticamente ({}), prosseguindo...".format(default_filepath)
    config_file = default_filepath
else:
    #pergunta ao usuário:
    config_file = get_location()

_config = dict(
  fromaddr = email,
  emaillist = None, #xml
  subject  = None, #xml
  message  = None, #xml
  #SMTP params
  server = 'smtp.gmail.com',
  port = 587, #TLS/STARTTLS
  username = email,
  password = email_passswd,
)
#lemos config.xml
try:
    tree = xml.etree.ElementTree.parse(config_file)
except xml.etree.ElementTree.ParseError:
    print "\n**ERRO: {} não é um arquivo válido. Verifique se o arquivo possui XML 'bem formado'".format(config_file)
    #sys.exit()

for child in tree.getroot():
  if _config.has_key(child.tag):
    _config.update({child.tag: child.text.strip()})

_config['emaillist'] = [addr.strip() for addr in _config['emaillist'].split("\n")]
#mime
msg = MIMEMultipart('alternative')
msg['Subject'] = _config['subject']
msg['From'] = _config['fromaddr']
msg['To'] = None # definido em loop
msg.attach(MIMEText(_config['message'].encode('UTF-8'), 'html', 'utf-8'))
emails_count = len(_config['emaillist'])

if (emails_count > 500):
    print POLICY_MESSAGE_2
    #sys.exit("Saindo...");


print "\nPreparando..."
print 'Conectando-se ao servidor SMTP, aguarde...'

server = smtplib.SMTP(_config.get('server'), _config.get('port'))
server.ehlo()
server.starttls()
server.ehlo() #Sim, novamente!

print "Autenticando usuário..."
try:
    server.login(_config.get('username'), _config.get('password'))
except smtplib.SMTPAuthenticationError:
    print "\n**ERRO: Informações usuário ou/e senha estão incorretas, saindo..."


print "Ok, iniciando envio...\n"

for index, toaddr in enumerate(_config['emaillist'], 1):
    msg['To'] = toaddr
    print '[{} de {}] Enviando email para "{}"... '.format(index, emails_count, toaddr),
    server.sendmail(_config['fromaddr'], toaddr, msg.as_string())
    time.sleep(1)
    print "Ok!"

print "\nEnvio concluído!"
server.quit()





""" From: https://support.google.com/mail/answer/13287

Incoming Mail (POP3) Server - requires SSL:	 pop.gmail.com
Use SSL: Yes
Port: 995
---
Outgoing Mail (SMTP) Server - requires TLS or SSL:	 smtp.gmail.com
Use Authentication: Yes
Port for TLS/STARTTLS: 587
Port for SSL: 465
Server timeouts	 Greater than 1 minute, we recommend 5
Full Name or Display Name:	 [your name]
Account Name or User Name:	 your full email address (including @gmail.com or @your_domain.com)
Email Address:	 your email address (username@gmail.com or username@your_domain.com)
Password:	 your Gmail password


---
Unless you're using recent mode to download mail to multiple clients, make sure
you've opted not to leave messages on the server. Your POP settings in Gmail
settings are what determines whether or not messages stay on the server,
so this setting in your client won't affect how Gmail handles your mail.

If your client does not support SMTP authentication, you won't be able to send
mail through your client using your Gmail address.

If you're having trouble sending mail but you've confirmed that encryption is
active for SMTP in your mail client, try to configure your SMTP server on a
different port (465 or 587).

"""
