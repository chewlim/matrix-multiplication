## Requirements & Assumptions

* Minimum PHP 7.4 (Assumptions)
* Laravel with vuejs
* PSR-12
* Strict type hinting
* Unit tests

## Notes
* Maximum matrix size is limit to 50x50.
* Minimum value of the matrix is 1.

## Setup and installation
  
* Run `composer install`

## Start development server

* Run `php artisan serve`
* Run `yarn dev`


## Todo

- [x] Setup new Laravel project 
- [x] Setup PSR-12 via Pint
- [x] Setup Vuejs with Typescript
- [x] Install tailwind
- [x] Install Ray (for debugging purposes)
- [x] Implement Multiply Matrices action
- [x] Endpoint to calculate multification of two matrices
- [x] Input form for matrices
- [x] Add request validation / ValidMatrices rule
- [x] Display validation errors
- [x] Display result (The resulting matrix should contain characters rather than numbers similar to excel columns. Examples: 1 => A, 26 => Z, 27 => AA, 28 => AB.)
- [x] Make matrices strictly type
- [x] Unit tests
  - [x] Test Calculate GET & POST endpoints
  - [X] Test ValidMetrices rule (Covered in `MultiplyMatricesTest`)
  - [x] Test MultiplyMatrices action

