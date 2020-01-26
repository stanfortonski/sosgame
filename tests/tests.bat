@echo off
echo SOSGAME TESTS
cd C:\Users\Programista\vendor\bin
call phpunit --bootstrap C:/Users/Programista/vendor/autoload.php --testdox --configuration phpunit.xml
pause
