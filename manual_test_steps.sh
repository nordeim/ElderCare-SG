#!/bin/bash
# Tests
composer phpunit -- tests/Feature/VirtualTourTest.php
composer phpunit
npm run build
npm run lint:accessibility
