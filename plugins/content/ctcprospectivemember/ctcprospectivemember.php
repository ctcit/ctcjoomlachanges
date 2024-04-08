<?php
/**
* Usage: {mosleader}
Displays the leader of trip calendar entries if user is registered or a warning message otherwise.*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class plgContentCTCProspectiveMember extends JPlugin
{
    /**
    * Link bot
    *
    * <b>Usage:</b>
    * <code>{prospectivememberresponse}</code>
    */
    public function onContentPrepare($context, &$row, $params, $page = 0) {
        if ( strpos( $row->text, '{prospectivememberresponse}' ) === false ) { // Quick check
            return true;
        }
        if (empty($_POST['email']) || empty($_POST['email2'])) {
            $row->text = "<p>An email address is required.</p>".
                         "<p>Please back up in the browser, correct the error and resubmit.</p>";
        } else if ($_POST['email'] != $_POST['email2']) {
            $row->text = "<p>Sorry, your form could not be processed as the two email fields do not match.</p>".
                         "<p>Please back up in the browser, correct the error and resubmit.</p>";
        } else {
            // Now send the email
            jimport( 'joomla.mail.helper' );
            jimport( 'joomla.mail.mail' );
            // The "name" field needs the leading underscore because for some reason Joola removes
            // the "name" attribute from the input if it's value is "name" ?!?
            $name = $_POST['_name'];
            $email = $_POST['email'];
            $email2 = $_POST['email2'];
            $phone = $_POST['phone'];
            $mobile = $_POST['mobile'];
            $address = $_POST['address'];
            $postcode = $_POST['postcode'];
            $howDidYouHear = $_POST['howdidyouhear'];
            $notes = $_POST['notes'];

            $body = <<<END
New CTC Prospective Member Contact

Name: $name
Email: $email
Email Veification: $email2
Phone: $phone
Mobile: $mobile
Address: $address
Postcode: $postcode
How did you hear about the CTC: $howDidYouHear
Notes: $notes
END;
            $user = JFactory::getUser();
            $config = JFactory::getConfig();
            $to = "new_members@ctc.org.nz"; // Todo look up a contact to find this
            $from = array("new_members@ctc.org.nz", "CTC website contact");

            # Invoke JMail Class
            $mailer = JFactory::getMailer();

            # Set sender array so that my name will show up neatly in your inbox
            $mailer->setSender($from);

            # Add a recipient -- this can be a single address (string) or an array of addresses
            $mailer->addRecipient($to);

            $mailer->setSubject("CTC prospective member");
            $mailer->setBody($body);

            # Send once you have set all of your options
            $result = $mailer->send();

            $row->text = "<p>Your form has been submitted.</p>".
                         "<p>Thank you for your interest in the CTC.</p>";
        }
        return true;
    }
}
?>
