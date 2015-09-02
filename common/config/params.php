<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'regex.mobile'=>'/^1[34578]\d{9}$/',
    'regex.name'=>'/^[\x{4e00}-\x{9fa5}]{2,11}$/u',//验证用户姓名
    'regex.username'=>'/^[\x{4e00}-\x{9fa5}A-Za-z][\x{4e00}-\x{9fa5}A-Za-z0-9_]{1,20}$/u',//验证用户名
    'regex.qq'=>'/^[1-9][0-9]{4,}$/',
    'regex.id'=>'/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/',
    'regex.email'=>'/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
    'regex.password'=>'/^.{6,}$/',

];
