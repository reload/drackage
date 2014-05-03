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
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for drackage routes.
 */
class DrackageController extends ControllerBase {
  /**
   * Shows the search form.
   */
  public function searchPage(Request $request) {
    $packages = $this->getPackages();
    $settings = array(
      'drackage_packages' => $packages,
    );
    $build['#attached']['js'][] = array('type' => 'setting', 'data' => $settings);

    $keys = '';
    if ($request->query->has('keys')) {
      $keys = trim($request->get('keys'));
    }

    // Build form first, it might redirect anyway.
    $build['form'] = $this->formBuilder()->getForm(new SearchForm($keys));

    if (!empty($keys)) {
      // Search packages.
      $build['results'] = array();
      foreach ($packages as $package) {
        if (strpos($package['name'], $keys) !== FALSE ||
          strpos($package['description'], $keys) !== FALSE) {
          $build['results'][] = array(
            '#theme' => 'drush_package_search_result',
            '#package' => $package,
          );
        }
      }

      if (empty($build['results'])) {
        $build['results'] = array(
          '#markup' => t('No packages found.'),
        );
      }
    }

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

    if (empty($package_info['package'])) {
      throw new NotFoundHttpException();
    }
    $package_info = $package_info['package'];

    $build['package'] = array(
      '#theme' => 'drush_package',
      '#package-json' => $package_info,
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

  /**
   * Fetches Drush packages listing from packagist.
   */
  protected function getPackages() {
    $packages_json = $this->packagistJson('search.json?tags[]=drush');
    $packages = array();
    if (!empty($packages_json['results'])) {
      // Sanitize the listing.
      foreach ($packages_json['results'] as $package) {
        $packages[] = array(
          'name' => check_plain($package['name']),
          'description' => check_plain($package['description']),
          'url' => check_plain($package['url']),
          'downloads' => check_plain($package['downloads']),
          'favers' => check_plain($package['favers']),
        );
      }
    }
    return $packages;
  }
}
