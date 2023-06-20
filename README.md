# yehchge\ycurl

curl http url

* [Installation](#installation)
* [Basic Usage](#basic-usage)

## Installation

``` bash
composer require "yehchge/ycurl"
```

## Basic Usage

curl url

``` php
<?php declare(strict_types=1);

include "vendor/autoload.php";

use yehchge\ycurl\CCurl;

// sample get
$CCurl = new CCurl();

$output = $CCurl->fetch('GET', 'https://example.com/');

if(preg_match("/Example Domain/", $output)){
    echo "A match was found.";
}else{
    echo "A match was not found.";
}
```
