Magento 2 code snippets.
Each main directory is a separate example module showing a different part of Magento 2.

Requirements:
- Magento Open Source ver. 2.4.8 (April 2025)
- PHP ver. 8.3 (8.4 supported)

Snippets:
- `CRUD` - data interface, model, resource model, collection, repository with search criteria, declarative schema and a data patch; frontend page `m2_crud/index/index` with a view model, CLI command `vendor:search:items`
- `CacheClearByTags` - cron job and CLI command `vendor:cache:clear` cleaning the cache by tags
- `CliEmail` - CLI command `vendor:send-email` sending an email template, with admin configuration
- `Di` - dependency injection playground with constructor arguments and a virtual type from `di.xml`, CLI command `vendor:di`
- `DynamicRows` - admin UI form with dynamic rows (`bss/row/index`)
- `Frontend` - frontend page `m2_frontend/index/index` with module assets, a RequireJS map alias and a `data-mage-init` module
- `Grid` - admin UI listing of categories with a mass delete action and an attribute join plugin (`dev_grid/index/index`)
- `Knockout` - Knockout.js templates, a custom UI component and a RequireJS mixin, in the admin and on the frontend
- `MVVM` - admin CRUD with a UI listing and form (`m2_mvvm_things`), frontend controller with a view model
- `NewtypesGraphQl` - custom GraphQL query `getNewtypes` with type and field resolvers and a cache identity

Installation:
- copy a snippet directory to `app/code/<Vendor>/<Module>` (e.g. `CRUD` to `app/code/M2/CRUD`), or install it with Composer from its `composer.json`
- `bin/magento setup:upgrade`

Code quality:
- coding standard: `composer create-project magento/magento-coding-standard ../magento-coding-standard`, then `../magento-coding-standard/vendor/bin/phpcs` (configured in `phpcs.xml.dist`)
- unit tests (`Test/Unit`), from the Magento root: `vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/<Vendor>/<Module>/Test/Unit`

Changes (after-time-fixes):

Security:
- `CRUD`: the frontend block no longer saves a row and dumps the whole table with `var_dump()` on every visit; it reads a limited list through the repository and the template escapes it
- `Grid`: the mass delete skips the tree root and the store root categories, which "select all" could delete; the listing page and its data source require `Magento_Catalog::categories` (the page was open to every admin role)
- `MVVM`, `DynamicRows`, `Grid`: actions that change data implement `HttpPostActionInterface`; the MVVM delete button posts with the form key
- `DynamicRows`: the save action validates the submitted rows before deleting the old ones (the GET save button used to wipe all rows); the index page checks its own ACL resource instead of the Import/Export one
- `MVVM`: the save action applies only the form fields to the model
- `Knockout`: the admin page checks the ACL resource from `acl.xml` instead of a menu id; the frontend template no longer dumps the customer data section
- `NewtypesGraphQl`: the category resolver returns only active categories of the current store; missing query input returns a GraphQL input error
- UI component data sources declare `<aclResource>`
- templates escape output with `$escaper`, and JavaScript in admin buttons is escaped with `escapeJs()`
- SQL in the `Grid` plugin uses quoted identifiers and bound values instead of string concatenation
- repositories no longer show raw database error messages to the user
- removed a hard-coded cache key that looked copied from a real shop (`CacheClearByTags`)
- `.idea/` is ignored

Magento 2.4.8 / PHP 8.4:
- declarative schema (`db_schema.xml`) and a data patch replace the deprecated `InstallSchema`/`InstallData` scripts in `CRUD` and `MVVM`; tables are lowercase, rows of the old mixed-case tables are migrated
- frontend controllers implement `HttpGetActionInterface` instead of extending the deprecated `Magento\Framework\App\Action\Action`; admin page controllers declare `HttpGetActionInterface`
- `OptionSourceInterface` replaces the deprecated `Option\ArrayInterface`; models are saved through resource models
- `CliEmail`: a configuration class replaces the `AbstractHelper` helper
- injected dependencies instead of the object manager (`CRUD`, `Grid`, `MVVM`)
- explicit nullable parameters (`?array`, `?string`), implicitly nullable ones are deprecated in PHP 8.4
- `declare(strict_types=1)` in every PHP file

Magento 2 conventions:
- service contracts: data interfaces with getters, setters and field constants, typed search results interfaces, repository preferences in `di.xml`, repositories using `CollectionProcessorInterface`
- view models (`ArgumentInterface`) with the generic template block instead of custom block classes (`CRUD`, `MVVM`, `Frontend`, `Knockout`)
- `DynamicRows` no longer borrows a button block from `Magento_CatalogRule`
- every `module.xml` lists its dependencies in `<sequence>` and has no `setup_version`; every module has a `composer.json`
- UI components use the `<settings>` syntax; layouts use `<body>` and urn schema locations, all XML validates against the Magento 2.4.8 XSDs
- assets are added with layout `<head>` instructions instead of script tags in templates; the standalone RequireJS app became Magento AMD modules; no `alert()` in JavaScript
- `phpcs.xml.dist` with the Magento 2 coding standard (no errors or warnings), unit tests, `i18n/en_US.csv` translations
- `NewtypesGraphQl`: `@cache` with a cache identity; class names in `schema.graphqls` match the code

Other fixes:
- `CliEmail` is a loadable module: added `registration.php`, `etc/module.xml` and the missing `M2_CliEmail::config` ACL resource; the command, the service and the email template work
- `Di`: the command arguments are typed with the injected objects (strings were a TypeError), and `Image` receives the virtual type arguments
- `Knockout`: fixed a PHP 8 error in the UI component, the data provider without a collection and the case of the UI component name
- `Grid`: the actions column called an undefined URL builder property
- `MVVM`: fixed the edit link route and the data persistor key

Known issues:
- `DynamicRows` depends on the `Vendor_DynamicCategory` module and `NewtypesGraphQl` on `Vendor_Newtypes` (the `Priorities` enum), neither is part of this repository
- `NewtypesGraphQl`: `Service\GetNewtypesList` is a stub that returns no newtypes
