# URGENT: PHPUnit Installation Fix

## Problem
PHPUnit binary is corrupted: `Class "PHPUnit\TextUI\Application" not found`

This is caused by Windows file locking (antivirus or Windows Search Indexer).

## Solution Steps

### Option 1: Disable Antivirus Temporarily (RECOMMENDED)

1. **Disable Windows Defender Real-time Protection**:
   - Open Windows Security
   - Go to "Virus & threat protection"
   - Click "Manage settings"
   - Turn OFF "Real-time protection"

2. **Delete vendor directory**:
   ```bash
   rmdir /s /q vendor
   ```

3. **Reinstall**:
   ```bash
   composer install
   ```

4. **Re-enable Windows Defender**

### Option 2: Exclude Project from Antivirus

1. Add project directory to Windows Defender exclusions:
   - Windows Security → Virus & threat protection
   - Manage settings → Exclusions
   - Add: `D:\kulyeahhh\sem6\PPPL\projek\lanina-patisserie`

2. Delete and reinstall:
   ```bash
   rmdir /s /q vendor
   composer install
   ```

### Option 3: Use Different PHP/Composer

If using XAMPP/Laragon, try switching to different PHP installation.

### Option 4: Manual Vendor Cleanup

```bash
# Stop any running PHP processes
taskkill /F /IM php.exe

# Wait 5 seconds
timeout /t 5

# Delete vendor
rmdir /s /q vendor

# Delete composer.lock
del composer.lock

# Clear composer cache
composer clear-cache

# Reinstall
composer install
```

### Option 5: Install PHPUnit Globally

```bash
# Install PHPUnit globally
composer global require phpunit/phpunit

# Run tests using global PHPUnit
%APPDATA%\Composer\vendor\bin\phpunit --testdox
```

## After Fix: Running Tests

Once PHPUnit is working, run:

```bash
# All tests
php artisan test
# or
vendor/bin/phpunit --testdox

# Specific test file
vendor/bin/phpunit tests/Unit/Models/TanggalTersediaTest.php --testdox

# Specific test suite
vendor/bin/phpunit --testsuite=Unit --testdox
vendor/bin/phpunit --testsuite=Feature --testdox
```

## Verify Installation

```bash
# Check PHPUnit version
vendor/bin/phpunit --version

# Should output something like:
# PHPUnit 10.5.63 by Sebastian Bergmann and contributors.
```

## Alternative: Skip Tests for Now

If you can't fix PHPUnit immediately, you can:

1. **Verify code manually** by:
   - Running the application
   - Testing checkout flow manually
   - Creating orders and checking slot counts
   - Verifying calendar displays correctly

2. **Test critical features**:
   - Create order → Check slot terisi increases
   - Fill slot → Try create another order → Should fail
   - Cancel order → Check slot terisi decreases

3. **Come back to automated tests later** when PHPUnit is fixed

## Test Files Are Ready

All 56 test cases are already created and ready to run:
- ✅ `tests/Unit/Models/TanggalTersediaTest.php` (11 tests)
- ✅ `tests/Feature/Controllers/CheckoutControllerTest.php` (14 tests)
- ✅ `tests/Feature/Controllers/TanggalControllerTest.php` (11 tests)
- ✅ `tests/Feature/Api/TanggalTersediaControllerTest.php` (11 tests)
- ✅ `tests/Feature/Integration/SlotLockingTest.php` (9 tests)

They just need PHPUnit to be working to execute.

## Contact Support

If none of these work, consider:
1. Using WSL (Windows Subsystem for Linux)
2. Using Docker for development
3. Using a different machine
4. Asking IT support to whitelist the project directory

---

**Status**: Tests are created ✅ | PHPUnit needs fixing ⚠️
