<?php

/**
 * @file
 * Contains \Drupal\Tests\Core\Cache\CacheFactoryTest.
 */

namespace Drupal\Tests\Core\Cache;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Component\Utility\Settings;
use Drupal\Core\Cache\CacheFactory;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the cache CacheFactory.
 *
 * @group Cache
 *
 * @coversDefaultClass \Drupal\Core\Cache\CacheFactory
 */
class CacheFactoryTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Cache factory test',
      'description' => 'Tests the cache CacheFactory.',
      'group' => 'Cache',
    );
  }

  /**
   * Test that the cache factory falls back to the built-in default service.
   *
   * @covers ::__construct
   * @covers ::get
   */
  public function testCacheFactoryWithDefaultSettings() {
    $settings = new Settings(array());
    $cache_factory = new CacheFactory($settings);

    $container = new ContainerBuilder();
    $cache_factory->setContainer($container);

    $builtin_default_backend_factory = $this->getMock('\Drupal\Core\Cache\CacheFactoryInterface');
    $container->set('cache.backend.database', $builtin_default_backend_factory);

    $render_bin = $this->getMock('\Drupal\Core\Cache\CacheBackendInterface');
    $builtin_default_backend_factory->expects($this->once())
      ->method('get')
      ->with('render')
      ->will($this->returnValue($render_bin));

    $actual_bin = $cache_factory->get('render');
    $this->assertSame($render_bin, $actual_bin);
  }

  /**
   * Test that the cache factory falls back to customized default service.
   *
   * @covers ::__construct
   * @covers ::get
   */
  public function testCacheFactoryWithCustomizedDefaultBackend() {
    $settings = new Settings(array(
      'cache' => array(
        'default' => 'cache.backend.custom',
      ),
    ));
    $cache_factory = new CacheFactory($settings);

    $container = new ContainerBuilder();
    $cache_factory->setContainer($container);

    $custom_default_backend_factory = $this->getMock('\Drupal\Core\Cache\CacheFactoryInterface');
    $container->set('cache.backend.custom', $custom_default_backend_factory);

    $render_bin = $this->getMock('\Drupal\Core\Cache\CacheBackendInterface');
    $custom_default_backend_factory->expects($this->once())
      ->method('get')
      ->with('render')
      ->will($this->returnValue($render_bin));

    $actual_bin = $cache_factory->get('render');
    $this->assertSame($render_bin, $actual_bin);
  }

  /**
   * Test that the cache factory picks the correct per-bin service.
   *
   * @covers ::__construct
   * @covers ::get
   */
  public function testCacheFactoryWithSpecifiedPerBinBackend() {
    $settings = new Settings(array(
      'cache' => array(
        'bins' => array(
          'render' => 'cache.backend.custom',
        ),
      ),
    ));
    $cache_factory = new CacheFactory($settings);

    $container = new ContainerBuilder();
    $cache_factory->setContainer($container);

    $custom_render_backend_factory = $this->getMock('\Drupal\Core\Cache\CacheFactoryInterface');
    $container->set('cache.backend.custom', $custom_render_backend_factory);

    $render_bin = $this->getMock('\Drupal\Core\Cache\CacheBackendInterface');
    $custom_render_backend_factory->expects($this->once())
      ->method('get')
      ->with('render')
      ->will($this->returnValue($render_bin));

    $actual_bin = $cache_factory->get('render');
    $this->assertSame($render_bin, $actual_bin);
  }

}
