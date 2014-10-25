#coding: utf8

import urllib, urllib2, json
from pprint import pprint

USER = 'prolancer user'
PASS = 'prolancer user pass'
AUTH_URL = 'http://www.prolancer.com.br/entrar/'
#TODO: CookieJar
COOKIES = 'ucur=BRL; country=BR; __utma=58728812.1842786659.1404528587.1408306521.1409447145.5; __utmz=58728812.1404528587.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); hblid=0sziPoM03S0bWImR339fn4upDSBAB230; olfsk=olfsk6668248362013995; lstnav=1408306466; PHPSESSID=cu36bqpboi6njl74ri13b1aag7; _we_wk_ss_lsf_=true; wcsid=G8neydNxSl0ssM21339fn6xpDSB5g3BC; _oklv=1409447143314,G8neydNxSl0ssM21339fn6xpDSB5g3BC; __utmb=58728812.2.10.1409447145; __utmc=58728812; _okbk=cd4=true,vi5=0,vi4=1409447149067,vi3=active,vi2=false,vi1=false,cd8=chat,cd6=0,cd5=away,cd3=false,cd2=0,cd1=0,; _ok=3917-494-10-9033'

data = {
	'txtEmail': USER,
	'txtPassword': PASS,
	'chkRemember': 'on',	
	#'redirect':  'http://www.prolancer.com.br/panel'
}

headers = { 
	'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0',	
	'Referer': 'http://www.prolancer.com.br/entrar', 	
	'Cookie': COOKIES,
}

#NOTE: Prolancer parece ter uma brecha de segurança no que diz respeito ao cookies
req = urllib2.Request(AUTH_URL, urllib.urlencode(data), headers)
response = urllib2.urlopen(req)
#content = response.read()

# ---

BIDDING_URL = 'http://www.prolancer.com.br/nubeServices.php'
headers.update({
	'Referer': 'Referer: http://www.prolancer.com.br/panel', 
	'Accept': 'application/json, text/javascript, */*; q=0.01',
})
url_params = {
	'a': 'getProfileHiringProjects', 
	'limit': 8,
	'page': 1,
}
BIDDING_URL += '?' + urllib.urlencode(url_params) 
req2 = urllib2.Request(BIDDING_URL, None, headers)
response = urllib2.urlopen(req2)
payload = json.loads(response.read())

def get_keys(_from=None):
	for k in _from.keys():
		if isinstance(k, dict):
			print get_keys(k)
		else:
			print k

projects = payload['projects']; l = len(projects)
infos = payload['info']


#for i, project in enumerate(projects, 1): print i, project['title']

for i, info in enumerate(infos, 1):	print i, infos[info]

