<?php

namespace Drupal\wechat_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Response;


/**
 * wechat service Controller
 */
class WechatServiceController extends ControllerBase {

    /**
     * The logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    protected $wechatApi;
    protected $wechatConfig;

    /**
     * {@inheritdoc} 
     **/
    public function __construct()
    {
        $this->logger = $this->getLogger('wechat access enter');
        $this->wechatConfig = $this->config('dld.wxapp.config');
    }

    /**
     * wechat access enter
     **/
    public function access() {

        $echostr = filter_input(INPUT_GET, 'echostr', FILTER_SANITIZE_SPECIAL_CHARS);
        //Retrun echostr for wechat server certification
        if(isset($echostr) || !is_null($echostr) || !empty($echostr)){
            echo $echostr;

            //$this->logger->notice('@data', array('@data' => $echostr));
            return new Response(null, 200, array());    //return empty to wechat
        }

        //$this->logger->notice('appID @data', array('@data' => $this->wechatConfig->get('AppID')));

        if( $this->checkSignature() ) {
            $xmldata = file_get_contents("php://input"); //get xml data
            $result = $this->routing_wechat_message($xmldata);  // handle received message 

            echo $result;

        }else{
            $this->logger->error('check signature error');
            echo '';
        }

        return new Response(null, 200, array());    //return empty to wechat
    }


    /**
     * Check signature for legal user
     * signature、timestamp、nonce、echostr
     * 1. 将token、timestamp、nonce三个参数进行字典序排序
     * 2. 将三个参数字符串拼接成一个字符串进行sha1加密
     * 3. 开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
     **/
    public function checkSignature() {
        $signature = filter_input(INPUT_GET, 'signature', FILTER_SANITIZE_SPECIAL_CHARS);
        $timestamp = filter_input(INPUT_GET, 'timestamp', FILTER_SANITIZE_SPECIAL_CHARS);
        $nonce = filter_input(INPUT_GET, 'nonce', FILTER_SANITIZE_SPECIAL_CHARS);

        $token = $this->wechatConfig->get('WX Token');

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * dispatch every message to web service
     **/
    public function routing_wechat_message($xmldata) {

        $result = 'success';

        //libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
        //the best way is to check the validity of xml by yourself. 
        //use libxml_disable_entity_loader is ok now.
        libxml_disable_entity_loader(true);
        $xmldata = simplexml_load_string($xmldata, 'SimpleXMLElement', LIBXML_NOCDATA);
        $postArray = $this->xml2array($xmldata);
        $this->logger->notice('postArray <pre>@data</pre>', array('@data' => print_r($postArray, TRUE)));
        
        //watchdog('wechat recv message', 'postArray <pre>@print_r</pre>', array('@print_r' => print_r($postArray, TRUE)));
//
//        //$MsgType = (string)$xmldata->MsgType;
//        $MsgType = $postArray['MsgType'];
//
//        $recv_msg = variable_get('wechat_recv_msg');
//
//        if($MsgType == 'event'){
//        //$event = (string)$xmldata->Event;
//        $event = $postArray['Event'];
//        $callback_func = $recv_msg[$MsgType][$event];
//        //    watchdog('wechat recv message',
//        //             'msg type @msgtype and event type @event, call @call_back',
//        //             array('@msgtype' => $MsgType, '@event' => $event, '@call_back' => $callback_func));
//        if(!isset($callback_func)){
//        watchdog('wechat recv message', 'can not find msg type @msgtype and event @event in recv msg callback array',
//        array('@msgtype' => $MsgType, '@event' => $event));
//
//        return $result;
//        }
//        }else{
//        $callback_func = $recv_msg[$MsgType];
//        //    watchdog('wechat recv message', 'msg type @msgtype, call @call_back', array('@msgtype' => $MsgType, '@call_back' => $callback_func));
//        if(!isset($callback_func)){
//        watchdog('wechat recv message', 'can not find msg type @msgtype in recv msg callback array',
//        array('@msgtype' => $MsgType));
//
//        return $result;
//        }
//        }
//        $cb = (string)$callback_func;
//        //watchdog('wechat recv message', 'callback function @msgtype ', array('@msgtype' => $cb));
//        //callback function invoked by message type and event
//        $result = $callback_func($postArray);
//
        return $result;

    }

    /**
     * Convert xml object to array
     */
    public function xml2array($xml) {
        $arr = array();

        foreach ($xml->children() as $r)
        {
            $t = array();
            if(count($r->children()) == 0)
            {
                $arr[$r->getName()] = strval($r);
            }
            else
            {
                $arr[$r->getName()][] = xml2array($r);
            }
        }
        return $arr;
    }
}


