<?php

namespace Nv2\Lib\Nv2\Service;

use Nv2\Lib\Nv2\Debug\Debug;

class ServiceRequest
{
    protected $serviceUrl;
    protected $params;
    protected $flags;

    protected function __construct()
    {
        $this->serviceUrl = null;
        $this->params = null;
    }    

    public static function create()
    {
        return new self();
    }

    public function param($name, $value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->params[] = array(
                    'name' => $name.'[]',
                    'value' => $v,                    
                );
            }
        } else {
            $this->params[] = array(
                'name' => $name,
                'value' => $value,
            );
        }
        return $this;
    }
    
    public function flag($name, $value)
    {
        $this->flags[$name] = $value;
        
        return $this;
    }

    public function execute()
    {
        return $this;
    }
    
    public function getUrl()
    {
        return '---';
    }
    
    public function getFlags()
    {
        return $this->flags;
    }

    public function retrieveFeedContent($url)
    {
        $hasError = false;
        $errorCode = 0;

        if (isset($_GET['debug']) && $_GET['debug'] == 2) {
            echo '<a href="' . $url . '">' . $url . '</a><br />';            
        }
        
        $ch = curl_init($url);
        $options = array(
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 10,        
        );
        curl_setopt_array($ch, $options);
        ob_start();
        curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $content = ob_get_contents();
        ob_clean();
        
        if (isset($_GET['debug']) && $_GET['debug'] == 1) {            
            Debug::addServiceRequest('', $url, $info['total_time']);
        }

        if ($info['http_code'] >= 400) {
            $hasError = true;
            $errorCode = $info['http_code'];
            $content = null;
        }

        if (!$content && $info['http_code'] < 400) {
            $hasError = true;
            $errorCode = 901;
            $content = null;
        }

        return array(
            'content' => $content,
            'hasError' => $hasError,
            'errorCode' => $errorCode
        );
    }
    
    private static function microtimeFloat()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}