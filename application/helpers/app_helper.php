<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Retorna a instancia atual do codeigniter
 */
if (!function_exists('app')) {
    function app()
    {
        static $APP;
        if (!$APP) {
            $APP =& get_instance();
        }
        return $APP;
    }
}

/**
 * Valida se a string é uma URL
 *
 * @param string $url Url a ser validada
 * @return boolean
 */
if (!function_exists('isUrl')) {
    function isUrl($url)
    {
        return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
}

/**
 * Retorna o conteúdo de uma url
 *
 * @param string $url Url onde serão capturado os dados
 * @return string conteúdo da url
 */
if (!function_exists('curlGetContents')) {
    function curlGetContents($url)
    {
        $output = '';
        if (function_exists('curl_init')) {
            $request = curl_init();
            curl_setopt_array(
                $request,
                array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true, // return web page
                    CURLOPT_HEADER => false, // don't return headers
                    CURLOPT_FOLLOWLOCATION => true, // follow redirects
                    // CURLOPT_USERAGENT => 'EstouSalvo - Agregador de Links - Sheegwa',
                    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
                    // CURLOPT_CAINFO => 'cacert.pem',
                    // CURLOPT_SSL_VERIFYHOST => false, // don't verify ssl
                    CURLOPT_SSL_VERIFYPEER => false, //
                    // CURLOPT_ENCODING => "", // handle all encodings
                    // CURLOPT_AUTOREFERER => true, // set referer on redirect
                    CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
                    CURLOPT_TIMEOUT => 120, // timeout on response
                    CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                    // CURLOPT_REFERER => $_SERVER['REQUEST_URI'],
                    // CURLOPT_POST => true, // i am sending post data
                    // CURLOPT_POSTFIELDS => $curl_data, // this are my post vars
                    // CURLOPT_VERBOSE => true,
                    // CURLOPT_USERAGENT => array_rand(
                    //     array(
                    //         'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                    //         'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                    //         'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                    //         'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                    //         'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'
                    //     )
                    // ),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
                        'Cache-Control: max-age=0',
                        // 'Cache-Control: no-cache, must-revalidate, max-age=0',
                        'Connection: keep-alive',
                        'Keep-Alive: 300',
                        'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                        // 'Accept-Language: en-us,en;q=0.5',
                        'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4',
                        'Referer: http://estousalvo.com',
                        'Pragma: no-cache',
                    ),
                )
            );
            $output = curl_exec($request);
            curl_close($request);
        } else {
            $output = file_get_contents($url);
        }
        // Return the output as a variable
        return $output;
    }
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
if (!function_exists('get_gravatar')) {
    function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
}

/**
 * Busca recursiva em um array pela chave e valor
 *
 * @param mixed $value Valor a ser procurado
 * @param array $array vetor com os dados
 * @param key $key chave para comparação
 */
if (!function_exists('findArrayByKey')) {
    function findArrayByKey($value, array $array, $key)
    {
        foreach ($array as $ar) {
            if ($ar[$key] == $value) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Verifica se a url pertence a um domain valido
 *
 * @param string $url
 * @param string $domain
 * @return boolean
 */
if (!function_exists('hasDomain')) {
    function hasDomain($url, $domain)
    {
        if (!isUrl($url)) {
            if (preg_match('/'.$domain.'/i', $url)) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Extrai o domain de uma url
 *
 * @param string $url
 * @param boolean $remove_www
 * @return string
 */
if (!function_exists('extractDomain')) {
    function extractDomain($url, $remove_www = false)
    {
        if (!preg_match('/^(http[s]?:\/\/)/', $url)) {
            $url = 'http://'.$url;
        }
        if (validar_url($url)) {
            $p_url = parse_url($url);
            $url = $p_url['host'];
        }
        return $remove_www ? remove_www($url) : $url;
    }
}

/**
 * Remove o www de uma URL
 *
 * @param string $url
 * @return string
 */
if (!function_exists('removeWww')) {
    function removeWww($url)
    {
        return preg_replace('/^([http|https]:\/\/)?\w{3}\.(.*)/i', '$2', $url);
    }
}


/**
 * Faz a validação da data
 *
 * @param string $date
 * @param string $format description
 * @return string
 */
if (!function_exists('validaData')) {
    function validaData($date, $format = 'Y-m-d H:i:s')
    {
        $v_date = date_create_from_format($format, $date);
        $v_date = date_format($v_date, $format);
        return ($v_date && $v_date == $date);
    }
}

/**
 * Converte a data de um formato para outro
 *
 * @param string $format fotmato atual
 * @param string $to_format novo formato
 * @param string $date data a ser convertida
 * @param string $timezone timezone atual
 * @return string data convertida
 */
if (!function_exists('converteData')) {
    function converteData($format, $to_format, $date, $timezone = null)
    {
        if (!$timezone) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }

        $d = date_create_from_format($format, $date, $timezone);
        return date_format($d, $to_format);
    }
}

/**
 * Encurta o nome de uma pessoa deixando apenas o primeiro e o último nome
 * @param string $name
 * @return string
 */
if (!function_exists('small_name')) {
    function small_name($nome)
    {
        try {
            $curUserNome = array();
            $i = 0;
            foreach (explode(' ', trim($nome)) as $value) {
                if ((strlen(trim($value)) > 2 && $i < 2) || $i == 0) {
                    $curUserNome[$i] = mb_strtolower($value, 'UTF-8');
                    $i++;
                }
            }
            return mb_convert_case(implode(' ', $curUserNome), MB_CASE_TITLE, 'UTF-8');
        } catch(Exception $e) {
            return $nome;
        }
    }
}
