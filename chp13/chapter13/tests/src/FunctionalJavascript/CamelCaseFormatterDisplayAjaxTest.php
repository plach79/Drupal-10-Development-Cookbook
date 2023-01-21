<?php

namespace Drupal\Tests\chapter13\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;

/**
 * Class CamelCaseFormatterDisplayAjaxTest
 *
 * @package Drupal\Tests\chapter13\FunctionalJavascript
 */
class CamelCaseFormatterDisplayAjaxTest extends WebDriverTestBase {

  use ContentTypeCreationTrait;

  /**
   * @var bool Disable schema checking.
   */
  protected $strictConfigSchema = FALSE;

  /**
   * @var string Theme to use during test.
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'field',
    'text',
    'node',
    'system',
    'filter',
    'user',
    'chapter13',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->createContentType(['type' => 'page']);

    // Create and store the field_chapter13_test field.
    FieldStorageConfig::create([
      'field_name' => 'field_chapter13_test',
      'entity_type' => 'node',
      'type' => 'string',
      'cardinality' => 1,
      'locked' => FALSE,
      'indexes' => [],
      'settings' => [
        'max_length' => 255,
        'case_sensitive' => FALSE,
        'is_ascii' => FALSE,
      ],
    ])->save();

    FieldConfig::create([
      'field_name' => 'field_chapter13_test',
      'field_type' => 'string',
      'entity_type' => 'node',
      'label' => 'chapter13 Camel Case Field',
      'bundle' => 'page',
      'description' => '',
      'required' => FALSE,
      'settings' => [
        'link_to_entity' => FALSE
      ],
    ])->save();

    // Set the entity display for testing to use our camel_case formatter.
    $entity_display = EntityViewDisplay::load('node.page.default');
    $entity_display->setComponent('field_chapter13_test',
      [
        'type' => 'camel_case',
        'region' => 'content',
        'settings' => [],
        'label' => 'hidden',
        'third_party_settings' => []
      ]);
    $entity_display->save();
  }

  /**
   * Test that a site visitor can see a string formatted with our custom
   * field CamelCaseFieldFormatter after time has elapsed from a custom AJAX
   * behavior that loads the field.
   *
   * @return void
   */
  public function testUserCanSeeFormattedString() {
    $this->drupalCreateNode(
      [
        'type' => 'page',
        'field_chapter13_test' => 'A user entered string'
      ]
    );

    $this->drupalGet('/node/1');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->pageTextContains('aUserEnteredString');
  }
}
