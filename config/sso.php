<?php

return [
    'allowed_redirects' => array_filter(explode(',', env('SSO_ALLOWED_REDIRECTS', ''))),
    'code_ttl'  => 60,
    'token_ttl' => 60 * 60 * 8,
];