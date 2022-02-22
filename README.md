# [wp-cubi-image-webp](https://github.com/globalis-ms/wp-cubi-image-webp)

[![Build Status](https://travis-ci.org/globalis-ms/wp-cubi-image-webp.svg?branch=master)](https://travis-ci.org/globalis-ms/wp-cubi-image-webp)
[![Latest Stable Version](https://poser.pugx.org/globalis/wp-cubi-image-webp/v/stable)](https://packagist.org/packages/globalis/wp-cubi-image-webp)
[![License](https://poser.pugx.org/globalis/wp-cubi-image-webp/license)](https://github.com/globalis-ms/wp-cubi-image-webp/blob/master/LICENSE.md)

Standalone image webp converter and provider WordPress plugin

[![wp-cubi](https://github.com/globalis-ms/wp-cubi/raw/master/.resources/wp-cubi-500x175.jpg)](https://github.com/globalis-ms/wp-cubi/)

## Overview

**wp-cubi-image-webp** is a very simple image converter and provider plugin for WordPress, meant to be used in a composer installation. It uses php gd extension to convert uploaded images into webp images (**jpg** and **png**).

## Requirements

The PHP [**gd**](https://www.php.net/manual/en/book.image.php) extension must be activated to convert images to webp

## Installation

- `composer require globalis/wp-cubi-image-webp`

## Configuration

Insert this chunk of code into your `.htaccess`
```apacheconf
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteCond %{HTTP_ACCEPT} image/webp
  RewriteCond %{REQUEST_FILENAME}.webp -f
  RewriteRule (.+)\.(jpg|jpeg|png)$ $1.$2.webp [T=image/webp,NC,L]
</IfModule>
```

## Bulk optimization

Bulk image optimization can be done using [wp-cli](http://wp-cli.org/) :

- Install **wp-cli** and ensure **wp-cubi-image-webp** is activated
- Usage: `wp webp generate <directories>... [--force=<false|true>]`
- Help: `wp help webp generate`
