# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## v2.0.0 - 2025-09-30
### 🚀 Major Update: DataTables v2.0 Migration
This is a major release that updates DataTables from v1.10.x to v2.0.x and migrates from Bower to NPM assets.

### ⚠️ Breaking Changes
- **Minimum PHP version upgraded** from `>=5.5.0` to `>=7.4.0`
- **Minimum Yii2 version upgraded** from `~2.0.13` to `~2.0.50`
- **Asset migration** from Bower (`bower-asset/*`) to NPM (`npm-asset/*`) packages
- **DataTables version upgrade** from `~1.10.15` to `^2.0`

### 📦 Added Dependencies
- `npm-asset/datatables.net`: ^2.0
- `npm-asset/datatables.net-dt`: ^2.0 (Default styling)
- `npm-asset/datatables.net-jqui`: ^2.0 (jQuery UI integration)
- `npm-asset/datatables.net-bs`: ^2.0 (Bootstrap 3 integration)
- `npm-asset/datatables.net-bs4`: ^2.0 (Bootstrap 4 integration)
- `npm-asset/datatables.net-bs5`: ^2.0 (Bootstrap 5 integration)
- `npm-asset/datatables.net-plugins`: ^2.0 (Additional plugins)

### 🗑️ Removed Dependencies
- `bower-asset/datatables`: ~1.10.15
- `bower-asset/datatables-plugins`: ~1.10.15
- `bower-asset/datatables.net-bs4`: ~1.10.15

### 🛠️ Changed
- **DataTableBaseAsset**: Updated source path to use NPM assets and fixed JS file references
- **DataTableBootstrapAsset**: Updated all Bootstrap integrations to use NPM packages
- **DataTableDefaultAsset**: Added proper initialization with DataTables v2.0 file structure
- **DataTableJuiAsset**: Updated jQuery UI integration to use NPM packages
- **File paths**: Fixed cross-platform compatibility (changed `\` to `/` in file paths)
- **Configuration**: Removed deprecated `fxp-asset` configuration block

### 📋 Migration Notes
Users upgrading from v1.x should:
1. Ensure PHP >= 7.4.0 and Yii2 >= 2.0.50
2. Run `composer update` to install new dependencies
3. Clear asset cache: `php yii asset/clear`
4. Test DataTable functionality, especially custom styling and server-side processing
5. Review any custom asset configurations for compatibility

### 🎯 Benefits
- Future-proof NPM asset management
- Improved performance and security with DataTables v2.0
- Better compatibility with modern Yii2 applications
- Simplified dependency management

## v1.1.3
### Added
- `DataTableAction` `query` parameter can be `Closure`

## v1.1.2
### Added
- Support for bootstrap 4/5 (autodetect required bootstrap version)
- Add `dataProvider` property to `DataTable`
  - if set, property `data` is autofilled with models from dataProvider
  - if models are found either in `dataProvider` or in `data`, column labels are loaded from
    `Model::attributes()`
- restore support for custom column definitions ([#52])

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

[#52]: https://github.com/NullRefExcep/yii2-datatables/issues/52
