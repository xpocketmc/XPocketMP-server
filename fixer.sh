#!/bin/bash
# Menjalankan PHPStan
~/.composer/vendor/bin/phpstan analyse src --level max

# Menjalankan PHP-CS-Fixer
~/.composer/vendor/bin/php-cs-fixer fix src
