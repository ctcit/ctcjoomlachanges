<?php
/**
* Usage: {mosleader}
Displays the leader of trip calendar entries if user is registered or a warning message otherwise.*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class plgContentLeader extends JPlugin
{
/**
* Link bot
*
* <b>Usage:</b>
* <code>{mosleader leader_and_phone}</code>
*/
    public function onContentPrepare($context, &$row, $params, $page = 0){
        if ( strpos( $row->text, 'mosleader' ) === false )  // Quick check
            return true;

        $regex = "#{mosleader +([^}]*)}#";
        $user = JFactory::getUser();
        if ($user->id <> 0) {
            //logged in
            $replacement = 'Leader: $1';
        }
        else /* Not logged in */ {
            $replacement = "To see leader contact details, please login. "
            ."If you're not a member, please see the "
            ."<a href=\"index.php\contact-us\">contacts page</a> "
            ."and contact the appropriate trip organiser and/or, "
            ."if you're interested in becoming a member, a new members' rep.";
        }
    
        // perform the replacement
        $row->text = preg_replace( $regex, $replacement, $row->text );
        return true;
    }
}
?>