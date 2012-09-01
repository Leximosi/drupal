<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views\argument_default\Php.
 */

namespace Drupal\views\Plugin\views\argument_default;

use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Default argument plugin to provide a PHP code block.
 *
 * @ingroup views_argument_default_plugins
 *
 * @Plugin(
 *   id = "php",
 *   title = @Translation("PHP Code")
 * )
 */
class Php extends ArgumentDefaultPluginBase {

  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['code'] = array('default' => '');

    return $options;
  }

  public function buildOptionsForm(&$form, &$form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['code'] = array(
      '#type' => 'textarea',
      '#title' => t('PHP contextual filter code'),
      '#default_value' => $this->options['code'],
      '#description' => t('Enter PHP code that returns a value to use for this filter. Do not use &lt;?php ?&gt;. You must return only a single value for just this filter. Some variables are available: the view object will be "$view". The argument handler will be "$argument", for example you may change the title used for substitutions for this argument by setting "argument->validated_title"".'),
    );

    // Only do this if using one simple standard form gadget
    $this->check_access($form, 'code');
  }

  /**
   * Only let users with PHP block visibility permissions set/modify this
   * default plugin.
   */
  public function access() {
    return user_access('use PHP for settings');
  }

  function get_argument() {
    // set up variables to make it easier to reference during the argument.
    $view = &$this->view;
    $argument = &$this->argument;
    ob_start();
    $result = eval($this->options['code']);
    ob_end_clean();
    return $result;
  }

}
