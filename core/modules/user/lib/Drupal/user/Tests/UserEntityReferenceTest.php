<?php
/**
 * @file
 * Contains \Drupal\user\Tests\UserEntityReferenceTest.
 */

namespace Drupal\user\Tests;

use Drupal\field\Field;
use Drupal\system\Tests\Entity\EntityUnitTestBase;

/**
 * User entity reference test cases.
 */
class UserEntityReferenceTest extends EntityUnitTestBase {

  /**
   * @var \Drupal\user\Entity\Role
   */
  protected $role1;

  /**
   * @var \Drupal\user\Entity\Role
   */
  protected $role2;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('entity_reference', 'user');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'User entity reference',
      'description' => 'Tests the user reference field functionality.',
      'group' => 'User',
    );
  }

  /**
   * {@inheritdoc}
   */
  function setUp() {
    parent::setUp();

    $this->role1 = entity_create('user_role', array(
      'id' => strtolower($this->randomName(8)),
      'label' => $this->randomName(8),
    ));
    $this->role1->save();

    $this->role2 = entity_create('user_role', array(
      'id' => strtolower($this->randomName(8)),
      'label' => $this->randomName(8),
    ));
    $this->role2->save();

    entity_reference_create_instance('user', 'user', 'user_reference', 'User reference', 'user');
  }

  /**
   * Tests user selection by roles.
   */
  function testUserSelectionByRole() {
    $fields = Field::fieldInfo()->getBundleInstances('user', 'user');
    $fields['user_reference']->settings['handler_settings']['filter']['role'] = array(
      $this->role1->id() => $this->role1->id(),
      $this->role2->id() => 0,
    );
    $fields['user_reference']->settings['handler_settings']['filter']['type'] = 'role';
    $fields['user_reference']->save();

    $user1 = $this->createUser(array('name' => 'aabb'));
    $user1->addRole($this->role1->id());
    $user1->save();

    $user2 = $this->createUser(array('name' => 'aabbb'));
    $user2->addRole($this->role1->id());
    $user2->save();

    $user3 = $this->createUser(array('name' => 'aabbbb'));
    $user3->addRole($this->role2->id());
    $user3->save();

    /** @var \Drupal\entity_reference\EntityReferenceAutocomplete $autocomplete */
    $autocomplete = \Drupal::service('entity_reference.autocomplete');

    $matches = $autocomplete->getMatches($fields['user_reference'], 'user', 'user', 'NULL', '', 'aabb');
    $this->assertEqual(count($matches), 2);
    $users = array();
    foreach ($matches as $match) {
      $users[] = $match['label'];
    }
    $this->assertTrue(in_array($user1->label(), $users));
    $this->assertTrue(in_array($user2->label(), $users));
    $this->assertFalse(in_array($user3->label(), $users));

    $matches = $autocomplete->getMatches($fields['user_reference'], 'user', 'user', 'NULL', '', 'aabbbb');
    $this->assertEqual(count($matches), 0, '');
  }
}
