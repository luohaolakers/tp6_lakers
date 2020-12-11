<?php
return [
    'key' => env('jwt.key', 'MvebwLPoQHKyhBBmaUutzGbxRWUaGxCv'),
    //签发者 可选
    'iss' => env('jwt.iss', ''),
    //接收该JWT的一方，可选
    'aud' => env('jwt.aud', ''),
    //过期时间
    'exp' => env('jwt.exp', 3600),
];