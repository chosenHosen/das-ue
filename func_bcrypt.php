<?php
function bcrypt_encode( $password, $rounds='08' )
{
    $salt = substr ( str_shuffle ( './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ) , 0, 22 );
    return crypt ( $password, '$2a$' . $rounds . '$' . $salt );
}