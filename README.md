# Lithium Bin

This application utilizes CouchDB and also powers the paste bin at [pastium.org](http://pastium.org). 

Amongst others it has the following features:

- Posting of code snippets
- List of recently made snippets
- Syntax highlighting
- Cookie remembering author name

## Requirements

 - A recent Lithium Core
 - PHP 5.3.1
 - CouchDB (tested with version 1.0.2)
 - GeSHI 1.0.8.4 (supplied with this package)

## Installation

Once you have set up the requirements above, in console use the provided installation command like so:

```sh
$ li3 bin install
```

This action should be run once to create database and design views or whenever design views changed and need to be updated.

If you have set up the application to know where to find Lithium

`app/config/bootstrap.php`  -> `LITHIUM_LIBRARY_PATH`
