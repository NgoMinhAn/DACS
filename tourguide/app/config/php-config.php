<?php
// Set the path to the SSL certificate (only if file exists)
$certPath = __DIR__ . '/../../cacert.pem';
if (file_exists($certPath)) {
    ini_set('curl.cainfo', $certPath);
    ini_set('openssl.cafile', $certPath);
} 