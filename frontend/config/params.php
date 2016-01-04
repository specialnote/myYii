<?php
return [
    'adminEmail' => 'admin@example.com',
    'form.username'=>'/^[\x{4e00}-\x{9fa5}A-Za-z][\x{4e00}-\x{9fa5}A-Za-z0-9_]{1,20}$/u',//验证用户名
    'form.mobile'=>'/^1[34578]\d{9}$/',//验证手机
    'form.password'=>'/^.{6,}$/',
];
