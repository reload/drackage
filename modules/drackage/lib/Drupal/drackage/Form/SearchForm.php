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
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drackage_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form['query'] = array(
      '#type' => 'textfield',
      '#title' => t('Search for'),
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
  public function submitForm(array &$form, array &$form_state) {}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, array &$form_state) {}

}
