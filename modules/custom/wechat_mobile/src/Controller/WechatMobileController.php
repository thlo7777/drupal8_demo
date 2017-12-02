<?php

namespace Drupal\wechat_mobile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DrupalKernelInterface;
use Drupal\Core\Url;
use Drupal\devel\DevelDumperManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides route responses for the container info pages.
 */
class WechatMobileController extends ControllerBase {

    /**
     * build simple test theme page
     * path /wechat/mobile/test
     **/
    public function GetTestThemePage() {
        return [
            '#markup' => '<p>' . $this->t('Simple page: The quick brown fox jumps over the lazy dog.') . '</p>',
        ];
    }

    public function GetRender() {
        $list[] = $this->t("First number was @number.", ['@number' => 1]);
        $list[] = $this->t("Second number was @number.", ['@number' => 2]);
        $list[] = $this->t('The total was @number.', ['@number' => 1 + 2]);

        $render_array['page_example_arguments'] = [
            // The theme function to apply to the #items.
            '#theme' => 'item_list',
            // The list itself.
            '#items' => $list,
            '#title' => $this->t('Argument Information'),
        ];

        return $render_array;
    }
}

