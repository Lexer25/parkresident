<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * User authorization library. Handles user login and logout, as well as secure
 * password hashing.
 *
 * @package    artonit/Curl
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Request_Curl {
    
    public static function get($url, array $headers) {
        return self::execute($url, 'GET', null, $headers);
    }
    
    public static function post($url, array $data , array $headers ) {
        return self::execute($url, 'POST', $data, $headers);
    }
    
    protected static function execute($url, $method = 'GET', $data = null, array $headers ) {
        $ch = curl_init();
    
        // Базовые настройки
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_SSL_VERIFYPEER => false, // Для разработки, в продакшене true
        );
           
        // Добавляем данные для POST/PUT
        if ($method !== 'GET' && $data) {
            $options[CURLOPT_POSTFIELDS] = is_array($data) ? http_build_query($data) : $data;
        }
        
        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Kohana_Exception('cURL error: :error', array(':error' => $error));
        }
        
        curl_close($ch);
        
        return array (
            'status' => $httpCode,
            'body' => $response
        );
    }
}
