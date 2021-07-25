@echo off
if not defined COMPOSER (
	set COMPOSER=composer-local.json
	echo "!!";
)
composer install