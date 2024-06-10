## INTRODUCTION

The primary use case for this module is to Create a custom entity named as "Custom Entity"

- Custom Entity comes with 5 fields - Title, Id, City, location, Population, State (also status, created & changed)

## REQUIREMENTS

Location field of the custom entity is dependent on Geofield contributed module - https://www.drupal.org/project/geofield

Same Needs to be installed 

## INSTALLATION
 - Clone the Repo into modules folder of a drupal site or clone into same.
 - Geofield through composer - composer require 'drupal/geofield:^1.57'
 - ddev drush en migration_assignment (This will enable both migration_assignment & geofield.)

## P.S.
For migration related configs refer submodule - Migrator.

## Custom Entity Collection
- Content >> Custom Entities
- Path : /admin/content/custom-entity

## Managing Custom Entity
- Structure >> Custom Entity
- Path : /admin/structure/custom-entity
