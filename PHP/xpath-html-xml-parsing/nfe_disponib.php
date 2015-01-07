<?php

/**
 * Rebuild all content under $node.
 * It works like javascript innerHTML method.
 * 
 * @param DOMNode $node
 * @return string HTML
 */
function dom_reconstruct(DOMElement $node){
	$dom = new DOMDocument(); 
	$dom->appendChild($dom->importNode($node, true)); 

	return $dom->saveHTML(); 
}

$query_params = '?versao=2.00&tipoConteudo=Skeuqr8PQBY=';
$url  = 'http://www.nfe.fazenda.gov.br/PORTAL/disponibilidade.aspx';
$url .= $query_params;
$request_content = file_get_contents($url);

$dom = new DOMDocument();
@$dom->loadHTML($request_content);
$xpath = new DOMXPath($dom);
$query = '//table[@id="ContentPlaceHolder1_gdvDisponibilidade"]';
$entries = $xpath->query($query);
$table = $entries->item(0); 

print dom_reconstruct($table);