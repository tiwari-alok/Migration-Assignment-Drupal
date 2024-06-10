<?php

declare(strict_types=1);

namespace Drupal\migration_assignment;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a custom entity entity type.
 */
interface CustomEntityInterface extends ContentEntityInterface, EntityChangedInterface {

}
