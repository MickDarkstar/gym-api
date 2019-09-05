<?php
// variables used for jwt
// $key's value must be your own and unique secret key.
define('SECRET_KEY', 'example_key');


// The rest is called the registered claim names. 
// The iss (issuer) claim identifies the principal that issued the JWT.
$iss = "http://example.org";
define('ISS', 'http://example.org');


// The aud (audience) claim identifies the recipients that the JWT is intended for. 
$aud = "http://example.com";
define('AUD', 'http://example.com');


// The iat (issued at) claim identifies the time at which the JWT was issued.
$iat = 1356999524;
define('IAT', 1356999524);


// The nbf (not before) claim identifies the time before which the JWT MUST NOT be accepted for processing.
$nbf = 1357000000;
define('NBF', 1356999524);

// You can use another useful claim name called exp (expiration time) which identifies the expiration time on or after which the JWT MUST NOT be accepted for processing.
