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
    $response      = curl_exec($ch);
    $response_info = curl_getinfo($ch);
    curl_close($ch);

    $status_code = $response_info['http_code'];
    $redirect_url = array_key_exists('redirect_url', $response_info) ? $response_info['redirect_url']: null;
    $last_http_method = $data ? 'post' : 'get';
    # opt curl
    $options = array(
        CURLOPT_AUTOREFERER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
    );

    if ($cookiejar) {
        $options += array(
            CURLOPT_COOKIEJAR  => $cookiejar,
            CURLOPT_COOKIEFILE => $cookiejar
        );
    }

    while ($status_code == 301 || $status_code == 302 || $status_code == 307 ) {
        $redirect_url = $response_info['redirect_url'];
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
        $response_info = curl_getinfo($ch);
        $status_code   = $response_info['http_code'];
        print "STATUS CODE: $status_code\n";
    }
    curl_close($ch);

    return $response;
}