<?php
/**
 * Segue redirecionamentos HTTP.
 * Isso é bastante útil se o host onde o script é executado não permite
 * redirecionamentos automáticos, onde as diretivas PHP "safe_mode" ou
 * "open_basedir" são habilitados.
 *
 */

/**
 * Segue redirecionamentos HTTP.
 * @param resource $ch Um manipulador cURL ativo
 * @param null $data
 * @param string $cookiejar
 * @param bool $debug
 * @return string
 *
 */
function handle_http_redirect(&$ch, $data=null, $cookiejar=null, $debug=false)
{
    $response         = curl_exec($ch);
    $response_info    = parse_headers($response);
//    $status_code      =  curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $status_code      =  $response_info['http_code'];
    $last_http_method = $data ? 'post' : 'get';

    curl_close($ch);

    # opt curl
    $options = array(
//        CURLOPT_VERBOSE =>1,
        CURLOPT_HEADER => true,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 0,
    );

    if ($cookiejar) {
        $options += array(
            CURLOPT_COOKIEJAR  => $cookiejar,
            CURLOPT_COOKIEFILE => $cookiejar
        );
    }

    while ($status_code == 301 || $status_code == 302 || $status_code == 307 ) {
        #print PHP_EOL . substr($response, 0, 1024*(1024/2)) . PHP_EOL . str_repeat('-', 120) . PHP_EOL;

        $redirect_url  = $response_info['location'];
        print_r($response_info);
        print "\n\n\n++ Encontrado redirecionamento, [$status_code]\n$redirect_url\n\n\n";

        # checa se requisição anterior foi POST e se deve ser repetida sob outro URI
        if ($status_code == 307 && 'post' == $last_http_method) {
            $options += array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($data),
            );
        } elseif ($status_code != 307 && 'post' == $last_http_method) {
            #  Pois depois de uma requisição POST e status de resposta != 307, geralemente
            # as subsequentes requisições serão GET.
            unset($options[CURLOPT_POST], $options[CURLOPT_POSTFIELDS]);
            $last_http_method = 'get';
        }
        # nova requisição
        $ch = curl_init($redirect_url);
        curl_setopt_array($ch, $options);
        $response      = curl_exec($ch);
//        print "\n\n\n\n$response\n\n\n\n";
        $response_info = parse_headers($response);
//        $status_code =  curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $status_code      =  $response_info['http_code'];
        print "STATUS CODE: $status_code\n";
    }
    curl_close($ch);

    return $response;
}

function parse_headers($response, $key=null)
{
    $headers = explode("\r\n", $response);
    $headers = array_filter($headers);
    $out = array();
    $out['http_code'] = null; # HTTP status code

    foreach ($headers as $header) {
        if (stripos($header, 'HTTP/1.') !== false) {
            $out['http_code'] = substr($header, 9, 3);
            # por que não me interessa uma linha do tipo "HTTP/1.1 ..."
            continue;
        }
        # header name: value
        $parts = explode(':', $header, 2);
        $header_name       = trim(strtolower($parts[0]));
        $header_value      = trim($parts[1]);
        $out[$header_name] = $header_value;
    }

    if ($key) {
        return $out[$key];
    }

    return $out;
}
