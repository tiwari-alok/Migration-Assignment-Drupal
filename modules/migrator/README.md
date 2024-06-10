## INTRODUCTION

The Migrator module Helps in migration of data from JSON to custom entity

The primary use case for this module is:

- Migrate Data from JSON
- Provdies an interface to reconfigure field mapping

## REQUIREMENTS

The migration script is based on parent module - Migration Assignment for custom entity
& Contributed module - MigratePlus, same needs to installed prior.

## INSTALLATION
 MigratePlus - https://www.drupal.org/project/migrate_plus
 installing using composer - composer require 'drupal/migrate_plus:^6.0'

## Migration Commands
- Import : ddev drush migrate-import cities_json
- Update : ddev drush migrate-import cities_json --update
- Stop : ddev drush migrate-stop cities_json 
- Rollback : ddev drush migrate-rollback cities_json 
- Tip - Use migrate tools contributed module to perform updates using UI - https://www.drupal.org/project/migrate_tools

# Update Field mapping
- Configuration >> Development >> Migration Mapping form
- Path : /admin/structure/custom-entity/migration-mapping

