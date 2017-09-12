<?php

namespace Drupal\wechat_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Response;


/**
 * wechat service Controller
 */
class WechatServiceController {
    public function access() {

        $echostr = filter_input(INPUT_GET, 'echostr', FILTER_SANITIZE_SPECIAL_CHARS);
        //Retrun echostr for wechat server certification
        if(isset($echostr) || !is_null($echostr) || !empty($echostr)){
            echo $echostr;
            \Drupal::logger('WechatServiceController')->notice('@data', array('@data' => $echostr));
            return new Response(null, 200, array());
        }
//        \Drupal::logger('WechatServiceController')->notice('hello');
//        return array( '#type' => 'markup',
//            '#markup' => $this->t('Hello, World!'),
//        );
    }
}


