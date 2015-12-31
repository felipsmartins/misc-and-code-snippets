<?php
/**
 * @author Kazumi <felipsmartins@protonmail.ch>
 */

/* log format
2015-02-15 17:12:19,657 4088 INFO ? openerp: database hostname: localhost
2015-02-15 17:12:19,657 4088 INFO ? openerp: database port: 5432
2015-02-15 17:12:19,657 4088 INFO ? openerp: database user: odoo
2015-02-15 17:12:23,372 4088 INFO ? openerp.service.server: HTTP service (werkzeug) running on 0.0.0.0:8069

ver mais em: http://pastebin.com/cdJ6zmiG
*/

define('LOG_DATE_PATTERN', '/\d{4}-\d{2}-\d{2}\s\d{2}\:\d{2}\:\d{2}/');
# Período
$dateRange = array(
	'start' => new DateTime('2015-02-15 17:20:00'), 
	'end'   => new DateTime('2015-02-18 21:36:41'),
);
$result   = array(); # linhas resultantes da pesquisa no log por data
$logFile  = fopen('/tmp/app2.log', 'r');
$thisDate = null; # cada data no log
$line     = null; # cada linha no log
# acho que é a abordagem mais performática
while (!feof($logFile)) {
	$thisLine = fgets($logFile); # ler até achar um line break

	if (preg_match(LOG_DATE_PATTERN, $thisLine, $matches)) {
		$thisDate = new DateTime($matches[0]);
		# E aqui acontece boa parte da mágica :)
		# Você poderia extrair esse trecho para uma função (algo como dateInRange) 
		# e separar a lógica para o filtro
		if ($thisDate >= $dateRange['start'] && $thisDate <= $dateRange['end']) {
			array_push($result, $thisLine);
		}
	}
}

print_r($result);

/* ======= NOTA ==========
	se a ordenação de datas no seu log estiver organizado de cima para baixo, 
	talvez você queira seguir essa lógica:

	# FAST PATH!
	if ($thisDate > $dateRange['end'] ) {
		break; //Não há porque continuar...
	} else {
		if ($thisDate >= $dateRange['start']) {
			array_push($result, $line);
		}
	}

	Isso evita disperdício de processamento analisando as próximas linhas inutilmente.
*/