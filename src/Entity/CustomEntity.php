<?php

declare(strict_types=1);

namespace Drupal\migration_assignment\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migration_assignment\CustomEntityInterface;

/**
 * Defines the custom entity entity class.
 *
 * @ContentEntityType(
 *   id = "custom_entity",
 *   label = @Translation("Custom Entity"),
 *   label_collection = @Translation("Custom Entities"),
 *   label_singular = @Translation("custom entity"),
 *   label_plural = @Translation("custom entities"),
 *   label_count = @PluralTranslation(
 *     singular = "@count custom entities",
 *     plural = "@count custom entities",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migration_assignment\CustomEntityListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\migration_assignment\Form\CustomEntityForm",
 *       "edit" = "Drupal\migration_assignment\Form\CustomEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "custom_entity",
 *   admin_permission = "administer custom_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/custom-entity",
 *     "add-form" = "/custom-entity/add",
 *     "canonical" = "/custom-entity/{custom_entity}",
 *     "edit-form" = "/custom-entity/{custom_entity}/edit",
 *     "delete-form" = "/custom-entity/{custom_entity}/delete",
 *     "delete-multiple-form" = "/admin/content/custom-entity/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.custom_entity.settings",
 * )
 */
final class CustomEntity extends ContentEntityBase implements CustomEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setRequired(TRUE)
    ->setTranslatable(TRUE)
    ->setRevisionable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('view', [
      'label' => 'hidden',
      'type' => 'string',
      'weight' => -5,
    ])
    ->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => -5,
    ])
    ->setDisplayConfigurable('form', TRUE);
    
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the custom entity was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the custom entity was last edited.'));

    return $fields;
  }

}
