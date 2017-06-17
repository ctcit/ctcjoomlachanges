<?php
/**
* Usage: {mosleader}
Displays the leader of trip calendar entries if user is registered or a warning message otherwise.*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class plgContentProspectiveMember extends JPlugin
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
            $name = $_POST['name'];
            $email = $_POST['email'];
            $email2 = $_POST['email2'];
            $phone = $_POST['phone'];
            $mobile = $_POST['mobile'];
            $address = $_POST['address'];
            $postcode = $_POST['postcode'];
            $notes = $_POST['notes'];

            $body = "name\t$name\nemail\t$email\nemail2\t$email2\nphone\t$phone\nmobile\t$mobile\naddress\t$address\npostcode\t$postcode\nnotes\t$notes";
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
    }
}
?>