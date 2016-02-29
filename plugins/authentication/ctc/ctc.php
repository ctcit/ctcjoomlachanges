<?php
/**
 * A Joomla3 authentication plug-in to authenticate against the CTC database.
 * Passwords in the CTC database follow the Joomla 1.2 convention, consisting
 * of an md5 encrypted password+salt, a colon and the salt (which is randomly
 * generated). See the method hashPassword
 *
 * @version    $Id: ctc.php 2015-11-18 $
 * @package    Joomla.Plugin
 * @subpackage Authentication.ctc
 * @license    GNU/GPL
 * @author     Ricahrd Lobb
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class PlgAuthenticationCtc extends JPlugin
{
    /**
     * Check the username/password combination against the CTC database.
     *
     * @access    public
     * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
     * @param     array     $options        Array of extra options
     * @param     object    $response       Authentication response object
     * @return    void
     */
    function onUserAuthenticate( $credentials, $options, &$response )
    {
        $db = JFactory::getDbo();
        $query  = $db->getQuery(true)
        ->select('*')
        ->from('ctcweb9_ctc.members')
        ->where('loginname=' . $db->quote($credentials['username']));

        $db->setQuery($query);
        $result = $db->loadObject();

        if (!$result) {
            $response->status = STATUS_FAILURE;
            $response->error_message = 'User does not exist';
            return;
        }

        /**
         * Check the password
         */

        $pass = $credentials['password'];
        if($result && $this->passwordsMatch($pass, $result->joomlaPasswordAdmin))
        {
            $response->email = $result->primaryEmail;
            $response->fullname = $result->firstName . ' ' . $result->lastName;
            $response->status = JAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
        }
        else
        {
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = 'Invalid username and password';
        }
    }
    public function onUserAfterLogin($options)
    {
        $user = $options['user'];
        return true;
    }
    public function onUserLogout($parameters, $options)
    {
        $user = $options['user'];
        return true;
    }


    // Return true iff the plaintext password matches the encrypted version
    // retrieved from the database.
    function passwordsMatch($rawpassword, $dbpassword)
    {
        $bits = explode(':', $dbpassword);
        if (count($bits) != 2) {
            return FALSE;
        }
        $salt = $bits[1];
        $expected = md5($rawpassword . $salt);
        return $expected === $bits[0];
    }


    // Hash the password following the standard Joomla approach. This is taken
    // from the ctcmodel model in CTCDB and is supplied here for reference
    // purposes only.
    function hashPassword($pass)
    {
        $saltChars  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $salt   = '';
        mt_srand(10000000*(double)microtime());
        for ($i = 0; $i < 16; $i++) {
            $salt .= $saltChars[mt_rand(0,61)];
        }
        $crypt = md5($pass.$salt);
        return $crypt.':'.$salt;
    }
}
