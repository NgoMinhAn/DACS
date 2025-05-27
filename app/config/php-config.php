<?php
// Set the path to the SSL certificate
$certPath = __DIR__ . '/../../cacert.pem';
ini_set('curl.cainfo', $certPath);
ini_set('openssl.cafile', $certPath); 