<?php

namespace Drupal\demo_test\Form;


use Drupal\wechat_api\Service\WechatApiService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the SimpleForm form controller.
 *
 * This example demonstrates a simple form with a singe text input element. We
 * extend FormBase which is the simplest form base class used in Drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class DtForm extends FormBase {

    protected $wechat_api;

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('service.wechatapi')
        );
    }

    public function __construct(WechatApiService $service) {
        $this->wechat_api = $service;
    }

  /**
   * Build the simple form.
   *
   * A build form method constructs an array that defines how markup and
   * other form elements are included in an HTML form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['title'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Title'),
          '#description' => $this->t('Title must be at least 5 characters in length.'),
          '#required' => TRUE,
        ];


        $node = \Drupal::entityManager()->getStorage('node')->load(16)->toArray();

        //dpm($node);
        $ref_ids = $node['field_ref_wechat_api'];
        //dpm($ref_ids);

//        $token = \Drupal::config('dld.wxapp.config')->get('get access token');
//        $AppID = \Drupal::config('dld.wxapp.config')->get('AppID');
//        $AppSecret = \Drupal::config('dld.wxapp.config')->get('AppSecret');
//        $token_url = t( $token, array( '@APPID' => $AppID, '@APPSECRET' => $AppSecret) )->render();

        //dpm($token_url);

        //$service = \Drupal::service('service.wechatapi');

        dpm($this->wechat_api->get_access_token());

//        $result = $this->wechat_api->wechat_php_curl_https_get($token_url);
//        \Drupal::logger('DtForm')->notice( 'data: <pre>@data</pre>', array('@data' => print_r($result, true)) );

        return $form;
    }

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller.  it must
   * be unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
    public function getFormId() {
        return 'demo_test_form';
    }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
//    $title = $form_state->getValue('title');
//    if (strlen($title) < 5) {
//      // Set an error for the form element with a key of "title".
//      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
//    }
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
     * This would normally be replaced by code that actually does something
     * with the title.
     */
//    $title = $form_state->getValue('title');
//    drupal_set_message(t('You specified a title of %title.', ['%title' => $title]));
  }

}
