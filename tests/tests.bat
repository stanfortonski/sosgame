@echo off
echo SOSGAME TESTS
call phpunit --bootstrap ../vendor/autoload.php --testdox --configuration phpunit.xml
pause
