# Migration Guide: Upgrading to yii2-datatables v2.0

This guide helps you migrate from yii2-datatables v1.x to v2.0, which includes a major update to DataTables v2.0 and migration from Bower to NPM assets.

## 🎯 Overview

Version 2.0 brings significant improvements but includes breaking changes that require attention during the upgrade process.

## ⚠️ Breaking Changes

### System Requirements
| Component | v1.x | v2.0 |
|-----------|------|------|
| PHP | >= 5.5.0 | **>= 7.4.0** |
| Yii2 | >= 2.0.13 | **>= 2.0.50** |
| DataTables | ~1.10.15 | **^2.0** |

### Asset Management
- **Bower assets** (`bower-asset/*`) have been completely **removed**
- **NPM assets** (`npm-asset/*`) are now used for all DataTables packages

## 🚀 Migration Steps

### Step 1: Check System Requirements
Ensure your system meets the new requirements:

```bash
# Check PHP version
php --version

# Check Yii2 version in your composer.json
composer show yiisoft/yii2
```

If you need to upgrade PHP or Yii2, do so before proceeding.

### Step 2: Update Composer Dependencies

Update your `composer.json`:

```json
{
    "require": {
        "nullref/yii2-datatables": "~2.0"
    }
}
```

Run the update:

```bash
composer update nullref/yii2-datatables
```

### Step 3: Clear Asset Cache

Clear all cached assets to ensure new NPM assets are properly loaded:

```bash
# Using Yii console command
php yii asset/clear

# Or manually delete the assets directory
rm -rf web/assets/*
```

### Step 4: Update Asset Bundle Configurations (If Any)

If you have custom asset bundle configurations, update them to use NPM paths:

#### Before (v1.x):
```php
'nullref\datatable\assets\DataTableAsset' => [
    'sourcePath' => '@bower/datatables/media',
    // ...
]
```

#### After (v2.0):
```php
'nullref\datatable\assets\DataTableAsset' => [
    'sourcePath' => '@npm/datatables.net',
    // ...
]
```

### Step 5: Remove Bower Asset Configuration

If you have `fxp-asset` configuration in your `composer.json`, you can remove it:

```json
// Remove this entire block:
"config": {
    "fxp-asset": {
        "installer-paths": {
            "bower-asset-library": "vendor/bower"
        }
    }
}
```

## 🧪 Testing Your Application

After migration, test these critical areas:

### 1. Basic DataTable Functionality
```php
<?= \nullref\datatable\DataTable::widget([
    'dataProvider' => $dataProvider,
    'columns' => ['id', 'name', 'email'],
]) ?>
```

### 2. Styling Options
Test all styling configurations you use:
- Default styling
- Bootstrap 3/4/5
- jQuery UI

### 3. Server-Side Processing
If you use server-side processing, verify your `DataTableAction` still works:

```php
// In your controller
public function actions()
{
    return [
        'datatables' => [
            'class' => 'nullref\datatable\DataTableAction',
            'query' => YourModel::find(),
        ],
    ];
}
```

### 4. Custom Asset Configurations
If you have custom asset configurations, ensure they work with the new NPM structure.

### 5. LinkColumn and Custom Columns
Test any custom column implementations:

```php
[
    'class' => 'nullref\datatable\LinkColumn',
    'url' => ['/model/view'],
    'label' => 'View',
]
```

## 🔧 Troubleshooting

### Asset Loading Issues
If assets aren't loading properly:

1. **Clear all caches:**
   ```bash
   php yii cache/flush-all
   php yii asset/clear
   ```

2. **Check asset bundle registration:**
   Enable debug mode and check browser developer tools for 404 errors.

3. **Verify NPM assets are installed:**
   ```bash
   composer show | grep npm-asset
   ```

### JavaScript Errors
If you encounter JavaScript errors:

1. **Check DataTables v2.0 compatibility:**
   DataTables v2.0 may have API changes. Review your custom JavaScript code.

2. **Update custom render functions:**
   Ensure custom column render functions are compatible with DataTables v2.0.

### Bootstrap Integration Issues
If Bootstrap styling isn't working:

1. **Verify Bootstrap version compatibility:**
   - Bootstrap 3: Uses `npm-asset/datatables.net-bs`
   - Bootstrap 4: Uses `npm-asset/datatables.net-bs4`
   - Bootstrap 5: Uses `npm-asset/datatables.net-bs5`

2. **Check table CSS classes:**
   ```php
   <?= \nullref\datatable\DataTable::widget([
       'tableOptions' => [
           'class' => 'table', // Required for Bootstrap
       ],
       // ...
   ]) ?>
   ```

## 📚 Additional Resources

- [DataTables v2.0 Documentation](https://datatables.net/)
- [DataTables Migration Guide](https://datatables.net/upgrade/)
- [Yii2 Asset Bundles](https://www.yiiframework.com/doc/guide/2.0/en/structure-assets)

## 🆘 Need Help?

If you encounter issues during migration:

1. Check our [GitHub Issues](https://github.com/NullRefExcep/yii2-datatables/issues)
2. Create a new issue with:
   - Your PHP and Yii2 versions
   - Complete error messages
   - Minimal code example reproducing the issue
   - Steps you've already tried

## 🎉 Benefits of v2.0

After successful migration, you'll enjoy:

- **Better Performance:** DataTables v2.0 performance improvements
- **Modern Dependencies:** NPM-based asset management
- **Future-Proof:** No more deprecated Bower dependencies
- **Security Updates:** Latest DataTables security patches
- **Improved Compatibility:** Better integration with modern Yii2 applications