# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## next release
### Added
- Support for bootstrap 4 (autodetect required bootstrap version)
- Add `dataProvider` property to `DataTable`
  - if set, property `data` is auto filled with models from dataProvider
  - if models are found either in `dataProvider` or in `data`, column labels are loaded from
    `Model::attributes()`
- restore support for custom column definitions (#52)

## v1.1.1
### Added
- Add `extraColumns` property to `DataTable`, `DataTableColumn`, `DataTableAction`

## v1.1.0
### Added
- Add `sClass` property for `DataTableColumn` class
- Add `title` property for `LinkColumn` class
### Changed
- Improve README
- Change `options` property to `linkOptions` at LinkColumn class
- Move asset classes to separate directory
- Update minimal php version to 5.5.0
- Reverse order CHANGELOG

## v1.0.4
### Added
- php 7.2 compatibility

## v1.0.3
### Added
- Data and response formatting in DataTableAction

## v1.0.2
### Fixed
- Server-side pagination

## v1.0.1
### Changed
- Improve README

## v1.0.0
### Changed 
- Move DataTable options to protected array. Add __set and __get methods.
