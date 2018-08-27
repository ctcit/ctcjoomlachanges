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
        ->from('ctc.members')
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
            $joomlaUser = JUser::getInstance();
            $id = (int) JUserHelper::getUserId($credentials['username']);
            if ($id){
                // User already in joomla db - ensure name and email are up to date
                // Could update other stuff here if necessary
                $joomlaUser->load($id);
		        $joomlaUser->set('name', $response->fullname);
		        $joomlaUser->set('email', $response->email);
                $joomlaUser->save();
            }else{
                // Make user in Joomla
  		        $joomlaUser->set('name', $response->fullname);
		        $joomlaUser->set('email', $response->email);
                $joomlaUser->set('username', $credentials['username']);
                $joomlaUser->groups['REGISTERED'] = 2;
                $joomlaUser->save();
            }
        }else{
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = 'Invalid username and password';
        }
    }

    // Return true iff the plaintext password matches the encrypted version
    // retrieved from the database.
    function passwordsMatch($rawpassword, $dbpassword)
    {
        $bits = explode(':', $dbpassword);
        if ($rawpassword === '' && $dbpassword === '')
            // This can only happen via direct db intervention
            // Intended for use when a passwork cannot be retrieved by any UI means
            return TRUE;
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
