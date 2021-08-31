<?php
if ( count($argv) <= 1 ) {
    die('Usage: php getUserID.php <cookie_file>');
} else {
    $cookies = unserialize( file_get_contents( $argv[1] ) );
    foreach( $cookies as $key => $value )
    {
        $_COOKIE[$key] = $value;
    }

    define('_JEXEC', 1);
    define('JPATH_BASE', __DIR__);
    require_once ( JPATH_BASE . '/includes/defines.php' );
    require_once ( JPATH_BASE . '/includes/framework.php' );

    $app = JFactory::getApplication('site');
    $user = JFactory::getUser();

    $userData = array(
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'email' => $user->email,
    );
    echo serialize( $userData );
}