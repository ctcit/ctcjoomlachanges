<?php
if ( count($argv) <= 1 ) {
    die('Usage: php getUserID.php <cookie_file>');
} else {
    $cookies = unserialize( file_get_contents( $argv[1] ) );
    foreach( $cookies as $key => $value )
    {
        $_COOKIE[$key] = $value;
    }
    $_SERVER['REQUEST_URI'] = "index.php";

    define('_JEXEC', 1);
    define('JPATH_BASE', __DIR__);
    require_once ( JPATH_BASE . '/includes/defines.php' );
    require_once ( JPATH_BASE . '/includes/framework.php' );

    // Boot the DI container
    $container = \Joomla\CMS\Factory::getContainer();

    /*
    * Alias the session service keys to the web session service as that is the primary session backend for this application
    *
    * In addition to aliasing "common" service keys, we also create aliases for the PHP classes to ensure autowiring objects
    * is supported.  This includes aliases for aliased class names, and the keys for aliased class names should be considered
    * deprecated to be removed when the class name alias is removed as well.
    */
    $container->alias('session.web', 'session.web.site')
        ->alias('session', 'session.web.site')
        ->alias('JSession', 'session.web.site')
        ->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
        ->alias(\Joomla\Session\Session::class, 'session.web.site')
        ->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');

    // Instantiate the application.
    $app = $container->get(\Joomla\CMS\Application\SiteApplication::class);

    $user = JFactory::getUser();

    $userData = array(
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'email' => $user->email,
    );
    echo serialize( $userData );
}
