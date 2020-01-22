# ClassicCommerce Unit Tests

## Initial Setup

1. Install [composer](https://getcomposer.org/) by following their [installation guide](https://getcomposer.org/download/). If you've installed it correctly, this should display the version:

    ```
    composer --version
    ```

2. Change to the plugin root directory and run `composer install` to install PHPUnit and other required dependencies:

    ```
    composer install
    ```

    If the dependencies have installed correctly, then this command should show you the PHPUnit version you have installed:

    ```
    ./vendor/bin/phpunit --version
    ```

3. Create a new, empty MySQL database on your computer for the tests to use, with a database user who has access to it.

4. Install WordPress and the WP Unit Test lib using the `install.sh` script. Change to the plugin root directory and type:

    ```
    tests/bin/install.sh <db-name> <db-user> <db-password> [db-host]
    ```

    Sample usage:

    ```
    tests/bin/install.sh woocommerce_tests root root
    ```

    **Important**: The `<db-name>` database will be created if it doesn't exist and **all data will be removed during testing**.

## Running Tests

Simply change to the plugin root directory and type:

```
./vendor/bin/phpunit
```

The tests will execute and you'll be presented with a summary. Code coverage documentation is automatically generated as HTML in the `tmp/coverage` directory.

You can run specific tests by providing the path and filename to the test class:

```
./vendor/bin/phpunit tests/unit-tests/api/orders
```

A text code coverage summary can be displayed using the `--coverage-text` option:

```
./vendor/bin/phpunit --coverage-text
```

## Writing Tests

* Each test file should roughly correspond to an associated source file, e.g. the `formatting/functions.php` test file covers code in the `wc-formatting-functions.php` file
* Each test method should cover a single method or function with one or more assertions
* A single method or function can have multiple associated test methods if it's a large or complex method
* Use the test coverage HTML report (under `tmp/coverage/index.html`) to examine which lines your tests are covering and aim for 100% coverage
* For code that cannot be tested (e.g. they require a certain PHP version), you can exclude them from coverage using a comment: `// @codeCoverageIgnoreStart` and `// @codeCoverageIgnoreEnd`. For example, see [`wc_round_tax_total()`](https://github.com/woocommerce/woocommerce/blob/35f83867736713955fa2c4f463a024578bb88795/includes/wc-formatting-functions.php#L208-L219)
* In addition to covering each line of a method/function, make sure to test common input and edge cases.
* Prefer `assertsEquals()` where possible as it tests both type & equality
* Remember that only methods prefixed with `test` will be run so use helper methods liberally to keep test methods small and reduce code duplication. If there is a common helper method used in multiple test files, consider adding it to the `WC_Unit_Test_Case` class so it can be shared by all test cases
* Filters persist between test cases so be sure to remove them in your test method or in the `tearDown()` method.
* Use data providers where possible. Be sure that their name is like `data_provider_function_to_test` (i.e. the data provider for `test_is_postcode` would be `data_provider_test_is_postcode`). Read more about data providers [here](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers).

## Automated Tests

Tests are automatically run with [Travis-CI](https://travis-ci.org/woocommerce/woocommerce) for each commit and pull request.

## Code Style

Classic Commerce uses `phpcs` and the WooCommerce coding style rules, with a few small modifications.

Code style is automatically checked for each pull request, in all files that were modified in that pull request.  If you'd like to check the code style locally for the files you've modified, you can change to the plugin root directory and run the `tests/bin/local-phpcs.sh` script:

```
./tests/bin/local-phpcs.sh
```

Correct code style is **encouraged** but not currently required, due to the high number of pre-existing violations that appear whenever a file is modified.

## Code Coverage

Code coverage is available on [Scrutinizer](https://scrutinizer-ci.com/g/woocommerce/woocommerce/) and [Code Climate](https://codeclimate.com/github/woocommerce/woocommerce) which receives updated data after each Travis build.
