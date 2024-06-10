<?php

namespace Drupal\migrator\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Config Form for update migration field mapping.
 */
class FieldMappingForm extends ConfigFormBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Constructs a new MyService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityFieldManagerInterface $entity_field_manager) {
    parent::__construct($config_factory);
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * Creates an instance.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_field.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'field_mapping_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['field_mapping.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load teh config form settings.
    $config = $this->config('field_mapping.settings');
    // Get fields of the custom entity.
    $fields = $this->entityFieldManager->getFieldDefinitions('custom_entity', 'custom_entity');

    $core_fields = ['id', 'uuid', 'status', 'created', 'changed'];
    // Builds an array of fields as options.
    foreach ($fields as $field_id => $field) {
      // Avoid showing core fields, as they shall not be modified.
      if (in_array($field_id, $core_fields)) {
        continue;
      }

      $options[$field_id] = ($field->getLabel() . ' (' . $field_id . ')');
    }

    // Fields are statically created on basis of JSON schema,
    // this can modified later on to be dynamic as per JSON.
    $form['id'] = [
      '#type' => 'select',
      '#title' => $this->t('ID'),
      '#options' => $options,
      '#default_value' => $config->get('id') ?? '',
      '#required' => TRUE,
    ];

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('City'),
      '#options' => $options,
      '#default_value' => $config->get('city') ?? '',
      '#required' => TRUE,
    ];

    $form['pop'] = [
      '#type' => 'select',
      '#title' => $this->t('POP'),
      '#options' => $options,
      '#default_value' => $config->get('pop') ?? '',
      '#required' => TRUE,
    ];

    $form['state'] = [
      '#type' => 'select',
      '#title' => $this->t('State'),
      '#options' => $options,
      '#default_value' => $config->get('state') ?? '',
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Load the migration configuration.
    $migration_id = 'cities_json';
    $config = $this->configFactory->getEditable('migrate_plus.migration.' . $migration_id);

    // Get the migration configuration process.
    $process = $config->get('process');

    // Resets all fields to empty.
    foreach ($process as $field => $value) {
      // Avoids location field as this needs to be associated with Geofield.
      if ($field == 'field_location') {
        continue;
      }

      $process[$field] = [
        'plugin' => 'default_value',
        'default_value' => '',
      ];
    }

    // Maps the destination field with source field from JSON.
    $process[$form_state->getValue('id')] = '_id';
    $process[$form_state->getValue('city')] = 'city';
    $process[$form_state->getValue('pop')] = 'pop';
    $process[$form_state->getValue('state')] = 'state';

    // Save the migration configuration.
    $config->set('process', $process)->save();

    // Updates the fieldmapping config form.
    $this->configFactory->getEditable('field_mapping.settings')
      ->set('id', $form_state->getValue('id'))
      ->set('city', $form_state->getValue('city'))
      ->set('pop', $form_state->getValue('pop'))
      ->set('state', $form_state->getValue('state'))
      ->save();

    // Currently Flushing all caches as tags are weirdly not working.
    /* @todo: Invalidate cache using tags instead of flushing all caches.
    Cache::invalidateTags(['migrate_plus.migration.' . $migration_id]); */
    drupal_flush_all_caches();

    // Throwing status message.
    $this->messenger()->addMessage($this->t('Field mapping has been updated.'));
  }

}
