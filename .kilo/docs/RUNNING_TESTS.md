# Running Tests - Troubleshooting Guide

## Issue: PHPUnit Not Working

If you encounter `Class "PHPUnit\TextUI\Application" not found` error, follow these steps:

### Solution 1: Reinstall Vendor Dependencies

```bash
# Delete vendor directory
rm -rf vendor

# Delete composer.lock
rm composer.lock

# Reinstall all dependencies
composer install
```

### Solution 2: Clear Composer Cache

```bash
composer clear-cache
composer install
```

### Solution 3: Use Composer Scripts

Add to `composer.json`:

```json
{
    "scripts": {
        "test": "phpunit --testdox",
        "test-unit": "phpunit --testsuite=Unit --testdox",
        "test-feature": "phpunit --testsuite=Feature --testdox",
        "test-coverage": "phpunit --coverage-html coverage"
    }
}
```

Then run:
```bash
composer test
composer test-unit
composer test-feature
```

### Solution 4: Manual PHPUnit Installation

```bash
composer require --dev phpunit/phpunit:^10.5
```

### Solution 5: Use Laravel Artisan (if available)

Some Laravel versions have built-in test command:
```bash
php artisan test
```

## Running Tests After Fix

### Run All Tests
```bash
vendor/bin/phpunit
# or
./vendor/bin/phpunit
# or on Windows
vendor\bin\phpunit.bat
```

### Run Specific Test Suite
```bash
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Feature
```

### Run Specific Test File
```bash
vendor/bin/phpunit tests/Unit/Models/TanggalTersediaTest.php
vendor/bin/phpunit tests/Feature/Controllers/CheckoutControllerTest.php
```

### Run with Test Output
```bash
vendor/bin/phpunit --testdox
```

### Run with Coverage
```bash
vendor/bin/phpunit --coverage-html coverage
```

## Test Files Created

### Unit Tests (11 tests)
- `tests/Unit/Models/TanggalTersediaTest.php`

### Feature Tests (36 tests)
- `tests/Feature/Controllers/CheckoutControllerTest.php` (14 tests)
- `tests/Feature/Controllers/TanggalControllerTest.php` (11 tests)
- `tests/Feature/Api/TanggalTersediaControllerTest.php` (11 tests)

### Integration Tests (9 tests)
- `tests/Feature/Integration/SlotLockingTest.php` (9 tests)

### Factory
- `database/factories/TanggalTersediaFactory.php`

## Expected Test Results

All 56 tests should pass:
- ✅ TanggalTersedia model accessors work correctly
- ✅ Slot locking prevents race conditions
- ✅ Checkout validates slot availability
- ✅ Calendar displays orders and slots correctly
- ✅ API returns correct data format

## Common Test Failures

### 1. Database Migration Issues
**Error**: Table doesn't exist

**Fix**:
```bash
php artisan migrate:fresh
```

### 2. Factory Issues
**Error**: Unknown column

**Fix**: Check that factory definitions match model fillable fields

### 3. Route Issues
**Error**: Route not found

**Fix**: Check `routes/web.php` and `routes/api.php`

### 4. Middleware Issues
**Error**: Unauthenticated

**Fix**: Tests use `actingAs()` to authenticate users

## Verifying Test Coverage

After tests pass, verify critical features:

1. **Slot Locking**
   - Unpaid orders lock slots ✓
   - Race conditions prevented ✓
   - Cancelled orders don't count ✓

2. **Checkout Flow**
   - Validates slot availability ✓
   - Rejects full/inactive slots ✓
   - Creates order with unpaid status ✓

3. **Calendar**
   - Groups orders by date ✓
   - Shows slot info ✓
   - Calculates statistics ✓

4. **API**
   - Returns available dates ✓
   - Correct response format ✓
   - Validates parameters ✓

## Next Steps

Once tests are running:

1. Run full test suite: `vendor/bin/phpunit`
2. Check coverage: `vendor/bin/phpunit --coverage-text`
3. Fix any failing tests
4. Add more tests for remaining features (SlotController, CartController)

## Contact

If issues persist, check:
- PHP version: `php -v` (should be 8.2+)
- Composer version: `composer --version`
- PHPUnit version: `vendor/bin/phpunit --version`
- Laravel version: `php artisan --version`
