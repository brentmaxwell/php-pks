# PHP-PKS: A PHP implementation for a HKP keyserver

Based on [PHKP](https://github.com/remko/phkp)

## About

PHP-PKS is an implementation of the 
[OpenPGP HTTP Keyserver Protocol (HKP)](http://ietfreport.isoc.org/all-ids/draft-shaw-openpgp-hkp-00.txt) in PHP.
It allows people to serve a PGP keyserver on most webservers, provided that the
webserver has [GnuPG](http://www.gnupg.org/) and PHP with `exec()` 
enabled. Searching, requesting and
submitting (optional) of keys are all supported.

## Disclaimer

This software is not production-ready. It probably contains
bugs and security leaks. Use at your own risk.

