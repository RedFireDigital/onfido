<?php
namespace Onfido;

use Onfido\Config;

class Request
{

    private $method = 'GET';
    private $endpoint = '/';

    private $url = 'https://api.onfido.com/v';

    private $curlHandle;

    public function __construct($method, $endpoint)
    {
        $this->method = $method;
        $this->endpoint = $endpoint;

        $this->curlHandle = curl_init();
    }

    public function send($params)
    {
        if (Config::init()->debug)
            var_dump(get_object_vars($params));

        $params = get_object_vars($params);

        $headers = Array('Authorization: Token token=' . Config::init()->token);

        $url_params = '';

        if ($this->method === 'PUT') {
            curl_setopt($this->curlHandle, CURLOPT_PUT, 1);

            $this->prepare_params($params);

            // var_dump($params);
            // var_dump(http_build_query($params));

            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $params);
        }
        if ($this->method === 'POST') {
            // $headers[] = "Content-type: multipart/form-data";

            curl_setopt($this->curlHandle, CURLOPT_POST, 1);

            $this->prepare_params($params);

            // var_dump($params);
            // var_dump(http_build_query($params));

            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $params);
        } else if ($this->method === 'GET') {
            $params['page'] = Config::init()->page;
            $params['per_page'] = Config::init()->per_page;

            $url_params = '?';

            $_url_params = Array();
            foreach ($params as $key => $value) {
                $_url_params[] = $key . '=' . urlencode($value);
            }

            $url_params .= implode('&', $_url_params);
        }


        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER,
            $headers
        );

        curl_setopt($this->curlHandle, CURLOPT_URL, $this->url . Config::init()->version . '/' . $this->endpoint . $url_params);

        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($this->curlHandle);

        return $this->processResponse($response);

        curl_close($this->curlHandle);
    }

    private function prepare_params(&$params)
    {
        $output = is_array($params) ? Array() : (is_object($params) ? new \stdClass() : null);

        $file_upload = false;

        foreach (is_array($params) ? $params : (is_object($params) ? get_object_vars($params) : null) as $k => $v) {
            if ($k === 'file' && ($v instanceof \CurlFile || strpos($v, '@') === 0)) {
                $file_upload = true;
            }

            if ($k === 'id' || $k === 'created_at' || $v === null)
                continue;

            if (is_array($output))
                $output[$k] = $v;
            else if (is_object($output))
                $output->$k = $v;
        }
        if ($file_upload)
            $params = $output;
        else
            $params = preg_replace('/\[[0-9]+\]/', '[]', urldecode(http_build_query($output)));
    }

    private function processResponse($response)
    {
        //var_dump("Response is");
        //var_dump($response);

        if ($response === false)
            return 'cURL Error: ' . curl_error($this->curlHandle);

        $httpCode = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        //if (strpos($httpCode, '2') !== 0)
            //return 'HTTP Error #' . $httpError . ' with Response: ' . $response;

        try {
            $data = json_decode($response, true);
            $data['httpCode'] = $httpCode;

            return $data;
        } catch (Execption $e) {
            return $this->error("Couldn't parse the response, or general error happened !, Exception: " . json_encode($e));
        }
    }

    private function error($error)
    {
        //die("Error #{$error->id} ({$error->type}): {$error->message} " . json_encode($error->fields));
        return $error;
        // exit;
    }

}
