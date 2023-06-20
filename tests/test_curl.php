<?php declare(strict_types=1);

namespace yehchge\ycurl;

include "../src/CCurl.php";

$CCurl = new CCurl();

$output = $CCurl->fetch('GET', 'https://example.com/');
// echo $output;

if(preg_match("/Example Domain/", $output)){
    echo "A match was found.";
}else{
    echo "A match was not found.";
}