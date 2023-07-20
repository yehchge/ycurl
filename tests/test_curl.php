<?php declare(strict_types=1);

namespace yehchge\ycurl;

include "../src/CCurl.php";

$CCurl = new CCurl();

$output = $CCurl->fetch('GET', 'https://example.com/');
// echo $output;

if(preg_match("/Example Domain/", $output)){
    echo "A match was found.\n";
}else{
    echo "A match was not found.\n";
}

$co_addr = '臺北市大安區金山南路二段55號';
$output = $CCurl->fetch('get', 'https://example.com/php/zipcode.php?address='.$co_addr);

echo $output;
