@echo off
echo Running PHPUnit Tests...
echo.

REM Set environment variables for testing
set APP_ENV=testing
set DB_CONNECTION=sqlite
set DB_DATABASE=:memory:

REM Run PHPUnit
php vendor/phpunit/phpunit/phpunit --testdox

echo.
echo Tests completed!
pause
