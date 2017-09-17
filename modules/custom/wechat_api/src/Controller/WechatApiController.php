<?php

namespace Drupal\wechat_api\Controller;

use Drupal\wechat_apai\Service;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller class for the Stream Wrapper Example.
 */
class WechatApiController extends ControllerBase {

    /**
     * @var use Drupal\wechat_apai\Service\WechatApiService.php
     **/
    protected $wechat_api_service;

    public function __construct(WechatApiService $service) {
        $this->wechat_api_service = $service;
    }
    /**
     * {@inheritdoc}
     **/
    public static function create(ContainerInterface $container) {
        return new static( $container->get('service.wechatapi') );
    }
 
    /**
     * Description page for the example. 
     **/
    public function description() {
        $build['intro'] = [
          '#markup' => '<p>wechat api interface </p>',
        ];

        return $build;
    }

}
