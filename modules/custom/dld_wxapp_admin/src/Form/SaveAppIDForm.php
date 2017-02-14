<?php

namespace Drupal\dld_wxapp_admin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * File test form class.
 *
 * @ingroup file_example
 */
class SaveAppIDForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'admin_appid_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // We access wechat app appID AppSecret configuration.
    $dld_app_config = \Drupal::service('config.factory')->getEditable('dld.wxapp.config');


    $form['description'] = array(
      '#markup' => $this->t('Save wechatp app appID and AppSecret to config.'),
    );

    $form['AppID'] = array(
        '#type' => 'textfield',
        '#title' => t('微信小程序appID'),
        '#size' => 60,
        '#default_value' =>  $dld_app_config->get('AppID'),
        '#maxlength' => 128,
        '#required' => TRUE,
    );

    $form['AppSecret'] = array(
        '#type' => 'textfield',
        '#title' => t('微信小程序AppSecret'),
        '#size' => 60,
        '#default_value' => $dld_app_config->get('AppSecret'),
        '#maxlength' => 128,
        '#required' => TRUE,
    );


    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // We don't use this, but the interface requires us to implement it.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $dld_app_config = \Drupal::service('config.factory')->getEditable('dld.wxapp.config');
    $dld_app_config->set('AppID', trim($form_state->getValue('AppID')));
    $dld_app_config->set('AppSecret', trim($form_state->getValue('AppSecret')));
    $dld_app_config->save();
  }

}

