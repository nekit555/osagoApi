<?php

class OsagoApi
{
    private $api; //SECRET API KEY
    /* URL for connect
     * EXAMPLE: http://site.ru
     */
    private $url;

    public function __construct($api = '', $url = '')
    {
        $this->checkConstructParams($api, $url);

        $this->api = $api;
        // Check URL on slash
        if (substr($this->url, -1) == '/' ) {
            $this->url = $url;
        } else {
            $this->url = $url . '/';
        }

    }

    /**
     * Check values on empty
     * @param $api string
     * @param $url string
     * @throws Exception
     */
    private function checkConstructParams($api, $url)
    {
        if ($api == '' || $url == '') {
            throw new Exception('API or URL not may be is empty');
        }
    }

    /**
     * Get Status list
     * @return string
     */
    public function getStatusList()
    {
        $result = $this->curl_get('statuses/', NULL);

        return $result;
    }

    /**
     * Send a POST request using cURL
     * @param string $url to request
     * @param array $post values to send
     * @param array $options for cURL
     * @return string
     */
    function curl_post($url, array $post = NULL, array $options = [])
    {
        $api_url = $this->url . $url;
        $defaults = [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $api_url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4
        ];
        if (!is_null($post)) {
            $defaults += [ CURLOPT_POSTFIELDS => http_build_query($post) ];
        }

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Send a GET request using cURL
     * @param string $url to request
     * @param array $get values to send
     * @param array $options for cURL
     * @return string
     */
    function curl_get($url, array $get = NULL, array $options = [])
    {
        $api_url = $this->url . $url;
        if (!is_null($get)) {
            $get = '?' . http_build_query($get);
        }
        $defaults = [
            CURLOPT_URL => $api_url . $get,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4
        ];

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));

        if( ! $result = curl_exec($ch))
        {
            $result = 'Not response from server';
        }
        $error_number     = curl_errno( $ch );
        $error_msg  = curl_error( $ch );

        if ($error_msg !== '') {
            return 'Error №' . $error_number . ' : ' . $error_msg;
        }
        curl_close($ch);
        return $result;
    }

}