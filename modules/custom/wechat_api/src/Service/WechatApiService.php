<?php

namespace Drupal\wechat_api\Service;

// These classes are used to implement a stream wrapper class.
use Drupal\Component\Utility\Html;
use Drupal\Core\Routing\UrlGeneratorTrait;

/**
 * wechat api service for all wechat api interface and include curl http interface
 **/
class WechatApiService {

    /**
     * Implment php5 curl api to get https url
     */
    public function wechat_php_curl_https_get($url, $h = false) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Edit: prior variable $postFields should be $postfields;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($h) {
            //include header information
            curl_setopt($ch, CURLOPT_HEADER, true); 
        }

        $result = curl_exec($ch);
        if(curl_errno($ch)) {
            curl_close($ch);
            return NULL;
        }

        if($h) {
            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
                return $result;
            }else{
                return NULL;
            }
        }

        curl_close($ch);
        return $result;
    }

    /**
     * Implment php5 curl api to post data to https url 
     **/
    public function wechat_php_curl_https_post($url, $postfields, $ct = '') {

        //$postfields = array('field1'=>'value1', 'field2'=>'value2');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        // Edit: prior variable $postFields should be $postfields;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($ct == 'json') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                    'Content-Length: ' . strlen($postfields))); 
        }

        //Keep this code to remind me use it for some chinese character just in case
        if($ct == 'utf-8') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 
                    'Content-Length: ' . strlen($postfields))); 
        }

        $result = curl_exec($ch);

        if(curl_errno($ch)) {
            curl_close($ch);
            return NULL;
        }

        curl_close($ch);
        return $result;
    }

    /**
     * Returns the name of the stream wrapper for use in the UI.
     * @return string
     *   The stream wrapper name.
     **/
    public function getName() {
        return t('File Example Session files');
    }


/*********************/

}
