<?php

/**
 * @file
 * Contains \Drupal\drackage\Controller\DrackageController.
 */

namespace Drupal\drackage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drackage\Form\SearchForm;
use Drupal\Core\Http\Client;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller routines for drackage routes.
 */
class DrackageController extends ControllerBase {
  /**
   * Shows the search form.
   */
  public function searchPage() {
    $settings = array(
      'drackage_packages' => $this->packagistJson('search.json?tags[]=drush'),
    );
    $build['#attached']['js'][] = array('type' => 'setting', 'data' => $settings);
    $build['form'] = $this->formBuilder()->getForm(new SearchForm());

    return $build;
  }

  /**
   * The _title_callback for the drackage.package_page route.
   */
  public function packagePageTitle($vendor, $package) {
    return $this->t('Package @vendor/@package', array('@vendor' => $vendor, '@package' => $package));
  }

  /**
   * Shows information about a package.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   Exception is thrown when we couldn't fetch any information about the
   *   given package.
   */
  public function packagePage($vendor, $package) {
    $package_info = $this->packagistJson('packages/' . $vendor . '/' . $package . '.json');

    if (empty($package_info)) {
      throw new NotFoundHttpException();
    }

    $build['package'] = array(
      '#type' => 'markup',
      '#markup' => check_plain(print_r($package_info, true)),
    );
    return $build;
  }

  /**
   * Make a request to Packagist and return the JSON response.
   */
  protected function packagistJson($path) {
    $http_client_options = array(
      'headers' => array(
        'User-Agent' => 'Drupal (+http://drupal.org/)',
      ),
    );
    $http_client = new Client($http_client_options);

    $json = array();
    try {
      $response = $http_client->get('https://packagist.org/' . $path);

      if ($response->getStatusCode() == 200) {
        $json = $response->json();
      }
    }
    catch (TransferException $e) {
      // Quietly swallow all Guzzle exceptions.
    }
    return $json;
  }
}
