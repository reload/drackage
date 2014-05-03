<?php

/**
 * @file
 * Contains \Drupal\drackage\Form\SearchForm.
 */

namespace Drupal\drackage\Form;

use Drupal\Core\Form\FormInterface;

/**
 * Drackage search form.
 */
class SearchForm implements FormInterface {
  /**
   * The keys searched for.
   */
  protected $keys;

  /**
   * Constructor.
   */
  public function __construct($keys) {
    $this->keys = $keys;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drackage_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form['keys'] = array(
      '#type' => 'textfield',
      '#title' => t('Search for'),
      '#default_value' => $this->keys,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Search'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    // Mirror cores way of redirecting to a get query.
    $form_state['redirect_route'] = array(
      'route_name' => 'drackage.search_page',
      'options' => array('query' => array('keys' => trim($form_state['values']['keys']))),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, array &$form_state) {}

}
