<?php

/**
 * @file
 * Contains \Drupal\drackage\Controller\DrackageController.
 */

namespace Drupal\drackage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drackage\Form\SearchForm;
use Drupal\Core\Http\Client;

/**
 * Controller routines for drackage routes.
 */
class DrackageController extends ControllerBase {
  /**
   * Shows the search form.
   */
  public function searchPage() {
    $http_client_options = array(
      'headers' => array(
        'User-Agent' => 'Drupal (+http://drupal.org/)',
      ),
    );
    $http_client = new Client($http_client_options);
    $response = $http_client->get('https://packagist.org/search.json?tags[]=drush');

    $packages = array();
    if ($response->getStatusCode() == 200) {
      $packages = $response->json();
      $settings = array(
        'drackage_packages' => $packages,
      );
      $build['#attached']['js'][] = array('type' => 'setting', 'data' => $settings);
    }

    $build['form'] = $this->formBuilder()->getForm(new SearchForm());

    return $build;
  }
}
