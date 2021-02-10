# DomPDF breaks with Twig's dump()

I admit, using dump() in a PDF is a bad idea anyways.
BUT: it is an easy example leading to a memory leak somewhere.

## How to reproduce

Assuming: git, PHP, composer and symfony are installed.

- Clone this repository (https://github.com/GenieTim/DomPDF-Twig-dump-error-repro/).
- Install the dependencies with `composer install`.
- Start the symfony server with `symfony server:start`.
- Point your browser to https://127.0.0.1:8000/index and wait for PHP to give up, failing to allocate more memory

## How to reproduce this repository

This repository aims to be a minimum case to reproduce. 
It was created by:

- `symfony new --full`
- `composer add nucleos/dompdf-bundle`
- `./bin/console make:controller`
- and a little bit of PHP and Twig code, to be found in `src/Controller/IndexController.php` and `templates/index/index.html.twig`
