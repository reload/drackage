<?php

/**
 * @file
 * Contains \Drupal\drackage\Controller\DrackageController.
 */

namespace Drupal\drackage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drackage\Form\SearchForm;

/**
 * Controller routines for drackage routes.
 */
class DrackageController extends ControllerBase {
  /**
   * Presents the site-wide contact form.
   *
   * @param \Drupal\contact\CategoryInterface $contact_category
   *   The contact category to use.
   *
   * @return array
   *   The form as render array as expected by drupal_render().
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   Exception is thrown when user tries to access non existing default
   *   contact category form.
   */
  public function searchPage() {
    $build['test'] = array(
      '#type' => 'markup',
      '#markup' => 'hello',
    );

    $build['form'] = $this->formBuilder()->getForm(new SearchForm());

    return $build;
  }
}
