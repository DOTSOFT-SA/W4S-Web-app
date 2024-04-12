<?php
$req_dump = print_r($_REQUEST, true);	
//$req_dump = file_get_contents("php://input");
file_put_contents('log.html', $req_dump."<br/>", FILE_APPEND);
$req_dump = print_r($_GET, true);	
file_put_contents('log.html', $req_dump."<br/>", FILE_APPEND);

file_put_contents('log.html', date('Y-m-d H:i:s')."<br/>", FILE_APPEND);


/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
date_default_timezone_set('Europe/Athens');
class PHPMailer
{
    /////////////////////////////////////////////////
    // PUBLIC VARIABLES
    /////////////////////////////////////////////////

    /**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
    var $Priority          = 3;

    /**
     * Sets the CharSet of the message.
     * @var string
     */
    var $CharSet           = "iso-8859-1";

    /**
     * Sets the Content-type of the message.
     * @var string
     */
    var $ContentType        = "text/plain";

    /**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
    var $Encoding          = "8bit";

    /**
     * Holds the most recent mailer error message.
     * @var string
     */
    var $ErrorInfo         = "";

    /**
     * Sets the From email address for the message.
     * @var string
     */
    var $From               = "root@localhost";

    /**
     * Sets the From name of the message.
     * @var string
     */
    var $FromName           = "Root User";

    /**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
    var $Sender            = "";

    /**
     * Sets the Subject of the message.
     * @var string
     */
    var $Subject           = "";

    /**
     * Sets the Body of the message.  This can be either an HTML or text body.
     * If HTML then run IsHTML(true).
     * @var string
     */
    var $Body               = "";

    /**
     * Sets the text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal Body.
     * @var string
     */
    var $AltBody           = "";

    /**
     * Sets word wrapping on the body of the message to a given number of 
     * characters.
     * @var int
     */
    var $WordWrap          = 0;

    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    var $Mailer            = "mail";

    /**
     * Sets the path of the sendmail program.
     * @var string
     */
    var $Sendmail          = "/usr/sbin/sendmail";
    
    /**
     * Path to PHPMailer plugins.  This is now only useful if the SMTP class 
     * is in a different directory than the PHP include path.  
     * @var string
     */
    var $PluginDir         = "";

    /**
     *  Holds PHPMailer version.
     *  @var string
     */
    var $Version           = "1.73";

    /**
     * Sets the email address that a reading confirmation will be sent.
     * @var string
     */
    var $ConfirmReadingTo  = "";

    /**
     *  Sets the hostname to use in Message-Id and Received headers
     *  and as default HELO string. If empty, the value returned
     *  by SERVER_NAME is used or 'localhost.localdomain'.
     *  @var string
     */
    var $Hostname          = "";

    /////////////////////////////////////////////////
    // SMTP VARIABLES
    /////////////////////////////////////////////////

    /**
     *  Sets the SMTP hosts.  All hosts must be separated by a
     *  semicolon.  You can also specify a different port
     *  for each host by using this format: [hostname:port]
     *  (e.g. "smtp1.example.com:25;smtp2.example.com").
     *  Hosts will be tried in order.
     *  @var string
     */
    var $Host        = "localhost";

    /**
     *  Sets the default SMTP server port.
     *  @var int
     */
    var $Port        = 25;

    /**
     *  Sets the SMTP HELO of the message (Default is $Hostname).
     *  @var string
     */
    var $Helo        = "";

    /**
     *  Sets SMTP authentication. Utilizes the Username and Password variables.
     *  @var bool
     */
    var $SMTPAuth     = false;

    /**
     *  Sets SMTP username.
     *  @var string
     */
    var $Username     = "";

    /**
     *  Sets SMTP password.
     *  @var string
     */
    var $Password     = "";

    /**
     *  Sets the SMTP server timeout in seconds. This function will not 
     *  work with the win32 version.
     *  @var int
     */
    var $Timeout      = 10;

    /**
     *  Sets SMTP class debugging on or off.
     *  @var bool
     */
    var $SMTPDebug    = false;

    /**
     * Prevents the SMTP connection from being closed after each mail 
     * sending.  If this is set to true then to close the connection 
     * requires an explicit call to SmtpClose(). 
     * @var bool
     */
    var $SMTPKeepAlive = false;

    /**#@+
     * @access private
     */
    var $smtp            = NULL;
    var $to              = array();
    var $cc              = array();
    var $bcc             = array();
    var $ReplyTo         = array();
    var $attachment      = array();
    var $CustomHeader    = array();
    var $message_type    = "";
    var $boundary        = array();
    var $language        = array();
    var $error_count     = 0;
    var $LE              = "\n";
    /**#@-*/
    
    /////////////////////////////////////////////////
    // VARIABLE METHODS
    /////////////////////////////////////////////////

    /**
     * Sets message type to HTML.  
     * @param bool $bool
     * @return void
     */
    function IsHTML($bool) {
        if($bool == true)
            $this->ContentType = "text/html";
        else
            $this->ContentType = "text/plain";
    }

    /**
     * Sets Mailer to send message using SMTP.
     * @return void
     */
    function IsSMTP() {
        $this->Mailer = "smtp";
    }

    /**
     * Sets Mailer to send message using PHP mail() function.
     * @return void
     */
    function IsMail() {
        $this->Mailer = "mail";
    }

    /**
     * Sets Mailer to send message using the $Sendmail program.
     * @return void
     */
    function IsSendmail() {
        $this->Mailer = "sendmail";
    }

    /**
     * Sets Mailer to send message using the qmail MTA. 
     * @return void
     */
    function IsQmail() {
        $this->Sendmail = "/var/qmail/bin/sendmail";
        $this->Mailer = "sendmail";
    }


    /////////////////////////////////////////////////
    // RECIPIENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds a "To" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddAddress($address, $name = "") {
        $cur = count($this->to);
        $this->to[$cur][0] = trim($address);
        $this->to[$cur][1] = $name;
    }

    /**
     * Adds a "Cc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
    */
    function AddCC($address, $name = "") {
        $cur = count($this->cc);
        $this->cc[$cur][0] = trim($address);
        $this->cc[$cur][1] = $name;
    }

    /**
     * Adds a "Bcc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddBCC($address, $name = "") {
        $cur = count($this->bcc);
        $this->bcc[$cur][0] = trim($address);
        $this->bcc[$cur][1] = $name;
    }

    /**
     * Adds a "Reply-to" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddReplyTo($address, $name = "") {
        $cur = count($this->ReplyTo);
        $this->ReplyTo[$cur][0] = trim($address);
        $this->ReplyTo[$cur][1] = $name;
    }


    /////////////////////////////////////////////////
    // MAIL SENDING METHODS
    /////////////////////////////////////////////////

    /**
     * Creates message and assigns Mailer. If the message is
     * not sent successfully then it returns false.  Use the ErrorInfo
     * variable to view description of the error.  
     * @return bool
     */
    function Send() {
        $header = "";
        $body = "";
        $result = true;

        if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
        {
            $this->SetError($this->Lang("provide_address"));
            return false;
        }

        // Set whether the message is multipart/alternative
        if(!empty($this->AltBody))
            $this->ContentType = "multipart/alternative";

        $this->error_count = 0; // reset errors
        $this->SetMessageType();
        $header .= $this->CreateHeader();
        $body = $this->CreateBody();

        if($body == "") { return false; }

        // Choose the mailer
        switch($this->Mailer)
        {
            case "sendmail":
                $result = $this->SendmailSend($header, $body);
                break;
            case "mail":
                $result = $this->MailSend($header, $body);
                break;
            case "smtp":
                $result = $this->SmtpSend($header, $body);
                break;
            default:
            $this->SetError($this->Mailer . $this->Lang("mailer_not_supported"));
                $result = false;
                break;
        }

        return $result;
    }
    
    /**
     * Sends mail using the $Sendmail program.  
     * @access private
     * @return bool
     */
    function SendmailSend($header, $body) {
        if ($this->Sender != "")
            $sendmail = sprintf("%s -oi -f %s -t", $this->Sendmail, $this->Sender);
        else
            $sendmail = sprintf("%s -oi -t", $this->Sendmail);

        if(!@$mail = popen($sendmail, "w"))
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        fputs($mail, $header);
        fputs($mail, $body);
        
        $result = pclose($mail) >> 8 & 0xFF;
        if($result != 0)
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        return true;
    }

    /**
     * Sends mail using the PHP mail() function.  
     * @access private
     * @return bool
     */
    function MailSend($header, $body) {
        $to = "";
        for($i = 0; $i < count($this->to); $i++)
        {
            if($i != 0) { $to .= ", "; }
            $to .= $this->to[$i][0];
        }

        if ($this->Sender != "" && strlen(ini_get("safe_mode"))< 1)
        {
            $old_from = ini_get("sendmail_from");
            ini_set("sendmail_from", $this->Sender);
            $params = sprintf("-oi -f %s", $this->Sender);
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, 
                        $header, $params);
        }
        else
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);

        if (isset($old_from))
            ini_set("sendmail_from", $old_from);

        if(!$rt)
        {
            $this->SetError($this->Lang("instantiate"));
            return false;
        }

        return true;
    }

    /**
     * Sends mail via SMTP using PhpSMTP (Author:
     * Chris Ryan).  Returns bool.  Returns false if there is a
     * bad MAIL FROM, RCPT, or DATA input.
     * @access private
     * @return bool
     */
    function SmtpSend($header, $body) {
        include_once($this->PluginDir . "class.smtp.php");
        $error = "";
        $bad_rcpt = array();

        if(!$this->SmtpConnect())
            return false;

        $smtp_from = ($this->Sender == "") ? $this->From : $this->Sender;
        if(!$this->smtp->Mail($smtp_from))
        {
            $error = $this->Lang("from_failed") . $smtp_from;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        // Attempt to send attach all recipients
        for($i = 0; $i < count($this->to); $i++)
        {
            if(!$this->smtp->Recipient($this->to[$i][0]))
                $bad_rcpt[] = $this->to[$i][0];
        }
        for($i = 0; $i < count($this->cc); $i++)
        {
            if(!$this->smtp->Recipient($this->cc[$i][0]))
                $bad_rcpt[] = $this->cc[$i][0];
        }
        for($i = 0; $i < count($this->bcc); $i++)
        {
            if(!$this->smtp->Recipient($this->bcc[$i][0]))
                $bad_rcpt[] = $this->bcc[$i][0];
        }

        if(count($bad_rcpt) > 0) // Create error message
        {
            for($i = 0; $i < count($bad_rcpt); $i++)
            {
                if($i != 0) { $error .= ", "; }
                $error .= $bad_rcpt[$i];
            }
            $error = $this->Lang("recipients_failed") . $error;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        if(!$this->smtp->Data($header . $body))
        {
            $this->SetError($this->Lang("data_not_accepted"));
            $this->smtp->Reset();
            return false;
        }
        if($this->SMTPKeepAlive == true)
            $this->smtp->Reset();
        else
            $this->SmtpClose();

        return true;
    }

    /**
     * Initiates a connection to an SMTP server.  Returns false if the 
     * operation failed.
     * @access private
     * @return bool
     */
    function SmtpConnect() {
        if($this->smtp == NULL) { $this->smtp = new SMTP(); }

        $this->smtp->do_debug = $this->SMTPDebug;
        $hosts = explode(";", $this->Host);
        $index = 0;
        $connection = ($this->smtp->Connected()); 

        // Retry while there is no connection
        while($index < count($hosts) && $connection == false)
        {
            if(strstr($hosts[$index], ":"))
                list($host, $port) = explode(":", $hosts[$index]);
            else
            {
                $host = $hosts[$index];
                $port = $this->Port;
            }

            if($this->smtp->Connect($host, $port, $this->Timeout))
            {
                if ($this->Helo != '')
                    $this->smtp->Hello($this->Helo);
                else
                    $this->smtp->Hello($this->ServerHostname());
        
                if($this->SMTPAuth)
                {
                    if(!$this->smtp->Authenticate($this->Username, 
                                                  $this->Password))
                    {
						
                        $this->SetError($this->Lang("authenticate"));
                        $this->smtp->Reset();
                        $connection = false;
                    }
                }
                $connection = true;
            }
            $index++;
        }
        if(!$connection)
            $this->SetError($this->Lang("connect_host"));

        return $connection;
    }

    /**
     * Closes the active SMTP session if one exists.
     * @return void
     */
    function SmtpClose() {
        if($this->smtp != NULL)
        {
            if($this->smtp->Connected())
            {
                $this->smtp->Quit();
                $this->smtp->Close();
            }
        }
    }

    /**
     * Sets the language for all class error messages.  Returns false 
     * if it cannot load the language file.  The default language type
     * is English.
     * @param string $lang_type Type of language (e.g. Portuguese: "br")
     * @param string $lang_path Path to the language file directory
     * @access public
     * @return bool
     */
    function SetLanguage($lang_type, $lang_path = "language/") {
		global $config;
	
		$lang_path = $config["physicalPath"] . "core/phpmailer/" . $lang_path;
			
		if(file_exists($lang_path.'phpmailer.lang-'.$lang_type.'.php'))
            include($lang_path.'phpmailer.lang-'.$lang_type.'.php');
        else if(file_exists($lang_path.'phpmailer.lang-en.php'))
            include($lang_path.'phpmailer.lang-en.php');
        else
        {
            $this->SetError("Could not load language file");
            return false;
        }
		
        $this->language = $PHPMAILER_LANG;
    
        return true;
    }

    /////////////////////////////////////////////////
    // MESSAGE CREATION METHODS
    /////////////////////////////////////////////////

    /**
     * Creates recipient headers.  
     * @access private
     * @return string
     */
    function AddrAppend($type, $addr) {
        $addr_str = $type . ": ";
        $addr_str .= $this->AddrFormat($addr[0]);
        if(count($addr) > 1)
        {
            for($i = 1; $i < count($addr); $i++)
                $addr_str .= ", " . $this->AddrFormat($addr[$i]);
        }
        $addr_str .= $this->LE;

        return $addr_str;
    }
    
    /**
     * Formats an address correctly. 
     * @access private
     * @return string
     */
    function AddrFormat($addr) {
        if(empty($addr[1]))
            $formatted = $addr[0];
        else
        {
            $formatted = $this->EncodeHeader($addr[1], 'phrase') . " <" . 
                         $addr[0] . ">";
        }

        return $formatted;
    }

    /**
     * Wraps message for use with mailers that do not
     * automatically perform wrapping and for quoted-printable.
     * Original written by philippe.  
     * @access private
     * @return string
     */
    function WrapText($message, $length, $qp_mode = false) {
        $soft_break = ($qp_mode) ? sprintf(" =%s", $this->LE) : $this->LE;

        $message = $this->FixEOL($message);
        if (substr($message, -1) == $this->LE)
            $message = substr($message, 0, -1);

        $line = explode($this->LE, $message);
        $message = "";
        for ($i=0 ;$i < count($line); $i++)
        {
          $line_part = explode(" ", $line[$i]);
          $buf = "";
          for ($e = 0; $e<count($line_part); $e++)
          {
              $word = $line_part[$e];
              if ($qp_mode and (strlen($word) > $length))
              {
                $space_left = $length - strlen($buf) - 1;
                if ($e != 0)
                {
                    if ($space_left > 20)
                    {
                        $len = $space_left;
                        if (substr($word, $len - 1, 1) == "=")
                          $len--;
                        elseif (substr($word, $len - 2, 1) == "=")
                          $len -= 2;
                        $part = substr($word, 0, $len);
                        $word = substr($word, $len);
                        $buf .= " " . $part;
                        $message .= $buf . sprintf("=%s", $this->LE);
                    }
                    else
                    {
                        $message .= $buf . $soft_break;
                    }
                    $buf = "";
                }
                while (strlen($word) > 0)
                {
                    $len = $length;
                    if (substr($word, $len - 1, 1) == "=")
                        $len--;
                    elseif (substr($word, $len - 2, 1) == "=")
                        $len -= 2;
                    $part = substr($word, 0, $len);
                    $word = substr($word, $len);

                    if (strlen($word) > 0)
                        $message .= $part . sprintf("=%s", $this->LE);
                    else
                        $buf = $part;
                }
              }
              else
              {
                $buf_o = $buf;
                $buf .= ($e == 0) ? $word : (" " . $word); 

                if (strlen($buf) > $length and $buf_o != "")
                {
                    $message .= $buf_o . $soft_break;
                    $buf = $word;
                }
              }
          }
          $message .= $buf . $this->LE;
        }

        return $message;
    }
    
    /**
     * Set the body wrapping.
     * @access private
     * @return void
     */
    function SetWordWrap() {
        if($this->WordWrap < 1)
            return;
            
        switch($this->message_type)
        {
           case "alt":
              // fall through
           case "alt_attachments":
              $this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
              break;
           default:
              $this->Body = $this->WrapText($this->Body, $this->WordWrap);
              break;
        }
    }

    /**
     * Assembles message header.  
     * @access private
     * @return string
     */
    function CreateHeader() {
        $result = "";
        
        // Set the boundaries
        $uniq_id = md5(uniqid(time()));
        $this->boundary[1] = "b1_" . $uniq_id;
        $this->boundary[2] = "b2_" . $uniq_id;

        $result .= $this->HeaderLine("Date", $this->RFCDate());
        if($this->Sender == "")
            $result .= $this->HeaderLine("Return-Path", trim($this->From));
        else
            $result .= $this->HeaderLine("Return-Path", trim($this->Sender));
        
        // To be created automatically by mail()
        if($this->Mailer != "mail")
        {
            if(count($this->to) > 0)
                $result .= $this->AddrAppend("To", $this->to);
            else if (count($this->cc) == 0)
                $result .= $this->HeaderLine("To", "undisclosed-recipients:;");
            if(count($this->cc) > 0)
                $result .= $this->AddrAppend("Cc", $this->cc);
        }

        $from = array();
        $from[0][0] = trim($this->From);
        $from[0][1] = $this->FromName;
        $result .= $this->AddrAppend("From", $from); 

        // sendmail and mail() extract Bcc from the header before sending
        if((($this->Mailer == "sendmail") || ($this->Mailer == "mail")) && (count($this->bcc) > 0))
            $result .= $this->AddrAppend("Bcc", $this->bcc);

        if(count($this->ReplyTo) > 0)
            $result .= $this->AddrAppend("Reply-to", $this->ReplyTo);

        // mail() sets the subject itself
        if($this->Mailer != "mail")
            $result .= $this->HeaderLine("Subject", $this->EncodeHeader(trim($this->Subject)));

        $result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
        $result .= $this->HeaderLine("X-Priority", $this->Priority);
        $result .= $this->HeaderLine("X-Mailer", "PHPMailer [version " . $this->Version . "]");
        
        if($this->ConfirmReadingTo != "")
        {
            $result .= $this->HeaderLine("Disposition-Notification-To", 
                       "<" . trim($this->ConfirmReadingTo) . ">");
        }

        // Add custom headers
        for($index = 0; $index < count($this->CustomHeader); $index++)
        {
            $result .= $this->HeaderLine(trim($this->CustomHeader[$index][0]), 
                       $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
        }
        $result .= $this->HeaderLine("MIME-Version", "1.0");

        switch($this->message_type)
        {
            case "plain":
                $result .= $this->HeaderLine("Content-Transfer-Encoding", $this->Encoding);
                $result .= sprintf("Content-Type: %s; charset=\"%s\"",
                                    $this->ContentType, $this->CharSet);
                break;
            case "attachments":
                // fall through
            case "alt_attachments":
                if($this->InlineImageExists())
                {
                    $result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s", 
                                    "multipart/related", $this->LE, $this->LE, 
                                    $this->boundary[1], $this->LE);
                }
                else
                {
                    $result .= $this->HeaderLine("Content-Type", "multipart/mixed;");
                    $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                }
                break;
            case "alt":
                $result .= $this->HeaderLine("Content-Type", "multipart/alternative;");
                $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
        }

        if($this->Mailer != "mail")
            $result .= $this->LE.$this->LE;

        return $result;
    }

    /**
     * Assembles the message body.  Returns an empty string on failure.
     * @access private
     * @return string
     */
    function CreateBody() {
        $result = "";

        $this->SetWordWrap();

        switch($this->message_type)
        {
            case "alt":
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/plain", "");
                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/html", "");
                
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                $result .= $this->EndBoundary($this->boundary[1]);
                break;
            case "plain":
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                break;
            case "attachments":
                $result .= $this->GetBoundary($this->boundary[1], "", "", "");
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE;
     
                $result .= $this->AttachAll();
                break;
            case "alt_attachments":
                $result .= sprintf("--%s%s", $this->boundary[1], $this->LE);
                $result .= sprintf("Content-Type: %s;%s" .
                                   "\tboundary=\"%s\"%s",
                                   "multipart/alternative", $this->LE, 
                                   $this->boundary[2], $this->LE.$this->LE);
    
                // Create text body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/plain", "") . $this->LE;

                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                // Create the HTML body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/html", "") . $this->LE;
    
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;

                $result .= $this->EndBoundary($this->boundary[2]);
                
                $result .= $this->AttachAll();
                break;
        }
        if($this->IsError())
            $result = "";

        return $result;
    }

    /**
     * Returns the start of a message boundary.
     * @access private
     */
    function GetBoundary($boundary, $charSet, $contentType, $encoding) {
        $result = "";
        if($charSet == "") { $charSet = $this->CharSet; }
        if($contentType == "") { $contentType = $this->ContentType; }
        if($encoding == "") { $encoding = $this->Encoding; }

        $result .= $this->TextLine("--" . $boundary);
        $result .= sprintf("Content-Type: %s; charset = \"%s\"", 
                            $contentType, $charSet);
        $result .= $this->LE;
        $result .= $this->HeaderLine("Content-Transfer-Encoding", $encoding);
        $result .= $this->LE;
       
        return $result;
    }
    
    /**
     * Returns the end of a message boundary.
     * @access private
     */
    function EndBoundary($boundary) {
        return $this->LE . "--" . $boundary . "--" . $this->LE; 
    }
    
    /**
     * Sets the message type.
     * @access private
     * @return void
     */
    function SetMessageType() {
        if(count($this->attachment) < 1 && strlen($this->AltBody) < 1)
            $this->message_type = "plain";
        else
        {
            if(count($this->attachment) > 0)
                $this->message_type = "attachments";
            if(strlen($this->AltBody) > 0 && count($this->attachment) < 1)
                $this->message_type = "alt";
            if(strlen($this->AltBody) > 0 && count($this->attachment) > 0)
                $this->message_type = "alt_attachments";
        }
    }

    /**
     * Returns a formatted header line.
     * @access private
     * @return string
     */
    function HeaderLine($name, $value) {
        return $name . ": " . $value . $this->LE;
    }

    /**
     * Returns a formatted mail line.
     * @access private
     * @return string
     */
    function TextLine($value) {
        return $value . $this->LE;
    }

    /////////////////////////////////////////////////
    // ATTACHMENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds an attachment from a path on the filesystem.
     * Returns false if the file could not be found
     * or accessed.
     * @param string $path Path to the attachment.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return bool
     */
    function AddAttachment($path, $name = "", $encoding = "base64", 
                           $type = "application/octet-stream") {
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;

        return true;
    }

    /**
     * Attaches all fs, string, and binary attachments to the message.
     * Returns an empty string on failure.
     * @access private
     * @return string
     */
    function AttachAll() {
        // Return text of body
        $mime = array();

        // Add all attachments
        for($i = 0; $i < count($this->attachment); $i++)
        {
            // Check for string attachment
            $bString = $this->attachment[$i][5];
            if ($bString)
                $string = $this->attachment[$i][0];
            else
                $path = $this->attachment[$i][0];

            $filename    = $this->attachment[$i][1];
            $name        = $this->attachment[$i][2];
            $encoding    = $this->attachment[$i][3];
            $type        = $this->attachment[$i][4];
            $disposition = $this->attachment[$i][6];
            $cid         = $this->attachment[$i][7];
            
            $mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
            $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $name, $this->LE);
            $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);

            if($disposition == "inline")
                $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);

            $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", 
                              $disposition, $name, $this->LE.$this->LE);

            // Encode as string attachment
            if($bString)
            {
                $mime[] = $this->EncodeString($string, $encoding);
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
            else
            {
                $mime[] = $this->EncodeFile($path, $encoding);                
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
        }

        $mime[] = sprintf("--%s--%s", $this->boundary[1], $this->LE);

        return join("", $mime);
    }
    
    /**
     * Encodes attachment in requested format.  Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeFile ($path, $encoding = "base64") {
        if(!@$fd = fopen($path, "rb"))
        {
            $this->SetError($this->Lang("file_open") . $path);
            return "";
        }
        $magic_quotes = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        $file_buffer = fread($fd, filesize($path));
        $file_buffer = $this->EncodeString($file_buffer, $encoding);
        fclose($fd);
        set_magic_quotes_runtime($magic_quotes);

        return $file_buffer;
    }

    /**
     * Encodes string to requested format. Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeString ($str, $encoding = "base64") {
        $encoded = "";
        switch(strtolower($encoding)) {
          case "base64":
              // chunk_split is found in PHP >= 3.0.6
              $encoded = chunk_split(base64_encode($str), 76, $this->LE);
              break;
          case "7bit":
          case "8bit":
              $encoded = $this->FixEOL($str);
              if (substr($encoded, -(strlen($this->LE))) != $this->LE)
                $encoded .= $this->LE;
              break;
          case "binary":
              $encoded = $str;
              break;
          case "quoted-printable":
              $encoded = $this->EncodeQP($str);
              break;
          default:
              $this->SetError($this->Lang("encoding") . $encoding);
              break;
        }
        return $encoded;
    }

    /**
     * Encode a header string to best of Q, B, quoted or none.  
     * @access private
     * @return string
     */
    function EncodeHeader ($str, $position = 'text') {
      $x = 0;
      
      switch (strtolower($position)) {
        case 'phrase':
          if (!preg_match('/[\200-\377]/', $str)) {
            // Can't use addslashes as we don't know what value has magic_quotes_sybase.
            $encoded = addcslashes($str, "\0..\37\177\\\"");

            if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str))
              return ($encoded);
            else
              return ("\"$encoded\"");
          }
          $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
          break;
        case 'comment':
          $x = preg_match_all('/[()"]/', $str, $matches);
          // Fall-through
        case 'text':
        default:
          $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
          break;
      }

      if ($x == 0)
        return ($str);

      $maxlen = 75 - 7 - strlen($this->CharSet);
      // Try to select the encoding which should produce the shortest output
      if (strlen($str)/3 < $x) {
        $encoding = 'B';
        $encoded = base64_encode($str);
        $maxlen -= $maxlen % 4;
        $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
      } else {
        $encoding = 'Q';
        $encoded = $this->EncodeQ($str, $position);
        $encoded = $this->WrapText($encoded, $maxlen, true);
        $encoded = str_replace("=".$this->LE, "\n", trim($encoded));
      }

      $encoded = preg_replace('/^(.*)$/m', " =?".$this->CharSet."?$encoding?\\1?=", $encoded);
      $encoded = trim(str_replace("\n", $this->LE, $encoded));
      
      return $encoded;
    }
    
    /**
     * Encode string to quoted-printable.  
     * @access private
     * @return string
     */
    function EncodeQP ($str) {
        $encoded = $this->FixEOL($str);
        if (substr($encoded, -(strlen($this->LE))) != $this->LE)
            $encoded .= $this->LE;

        // Replace every high ascii, control and = characters
        $encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
        // Replace every spaces and tabs when it's the last character on a line
        $encoded = preg_replace("/([\011\040])".$this->LE."/e",
                  "'='.sprintf('%02X', ord('\\1')).'".$this->LE."'", $encoded);

        // Maximum line length of 76 characters before CRLF (74 + space + '=')
        $encoded = $this->WrapText($encoded, 74, true);

        return $encoded;
    }

    /**
     * Encode string to q encoding.  
     * @access private
     * @return string
     */
    function EncodeQ ($str, $position = "text") {
        // There should not be any EOL in the string
        $encoded = preg_replace("[\r\n]", "", $str);

        switch (strtolower($position)) {
          case "phrase":
            $encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
          case "comment":
            $encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
          case "text":
          default:
            // Replace every high ascii, control =, ? and _ characters
            $encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
        }
        
        // Replace every spaces to _ (more readable than =20)
        $encoded = str_replace(" ", "_", $encoded);

        return $encoded;
    }

    /**
     * Adds a string or binary attachment (non-filesystem) to the list.
     * This method can be used to attach ascii or binary data,
     * such as a BLOB record from a database.
     * @param string $string String attachment data.
     * @param string $filename Name of the attachment.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return void
     */
    function AddStringAttachment($string, $filename, $encoding = "base64", 
                                 $type = "application/octet-stream") {
        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $string;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $filename;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = true; // isString
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;
    }
    
    /**
     * Adds an embedded attachment.  This can include images, sounds, and 
     * just about any other document.  Make sure to set the $type to an 
     * image type.  For JPEG images use "image/jpeg" and for GIF images 
     * use "image/gif".
     * @param string $path Path to the attachment.
     * @param string $cid Content ID of the attachment.  Use this to identify 
     *        the Id for accessing the image in an HTML form.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.  
     * @return bool
     */
    function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", 
                              $type = "application/octet-stream") {
    
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "inline";
        $this->attachment[$cur][7] = $cid;
    
        return true;
    }
    
    /**
     * Returns true if an inline attachment is present.
     * @access private
     * @return bool
     */
    function InlineImageExists() {
        $result = false;
        for($i = 0; $i < count($this->attachment); $i++)
        {
            if($this->attachment[$i][6] == "inline")
            {
                $result = true;
                break;
            }
        }
        
        return $result;
    }

    /////////////////////////////////////////////////
    // MESSAGE RESET METHODS
    /////////////////////////////////////////////////

    /**
     * Clears all recipients assigned in the TO array.  Returns void.
     * @return void
     */
    function ClearAddresses() {
        $this->to = array();
    }

    /**
     * Clears all recipients assigned in the CC array.  Returns void.
     * @return void
     */
    function ClearCCs() {
        $this->cc = array();
    }

    /**
     * Clears all recipients assigned in the BCC array.  Returns void.
     * @return void
     */
    function ClearBCCs() {
        $this->bcc = array();
    }

    /**
     * Clears all recipients assigned in the ReplyTo array.  Returns void.
     * @return void
     */
    function ClearReplyTos() {
        $this->ReplyTo = array();
    }

    /**
     * Clears all recipients assigned in the TO, CC and BCC
     * array.  Returns void.
     * @return void
     */
    function ClearAllRecipients() {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
    }

    /**
     * Clears all previously set filesystem, string, and binary
     * attachments.  Returns void.
     * @return void
     */
    function ClearAttachments() {
        $this->attachment = array();
    }

    /**
     * Clears all custom headers.  Returns void.
     * @return void
     */
    function ClearCustomHeaders() {
        $this->CustomHeader = array();
    }


    /////////////////////////////////////////////////
    // MISCELLANEOUS METHODS
    /////////////////////////////////////////////////

    /**
     * Adds the error message to the error container.
     * Returns void.
     * @access private
     * @return void
     */
    function SetError($msg) {
        $this->error_count++;
        $this->ErrorInfo = $msg;
    }

    /**
     * Returns the proper RFC 822 formatted date. 
     * @access private
     * @return string
     */
    function RFCDate() {
        $tz = date("Z");
        $tzs = ($tz < 0) ? "-" : "+";
        $tz = abs($tz);
        $tz = ($tz/3600)*100 + ($tz%3600)/60;
        $result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);

        return $result;
    }
    
    /**
     * Returns the appropriate server variable.  Should work with both 
     * PHP 4.1.0+ as well as older versions.  Returns an empty string 
     * if nothing is found.
     * @access private
     * @return mixed
     */
    function ServerVar($varName) {
        global $HTTP_SERVER_VARS;
        global $HTTP_ENV_VARS;

        if(!isset($_SERVER))
        {
            $_SERVER = $HTTP_SERVER_VARS;
            if(!isset($_SERVER["REMOTE_ADDR"]))
                $_SERVER = $HTTP_ENV_VARS; // must be Apache
        }
        
        if(isset($_SERVER[$varName]))
            return $_SERVER[$varName];
        else
            return "";
    }

    /**
     * Returns the server hostname or 'localhost.localdomain' if unknown.
     * @access private
     * @return string
     */
    function ServerHostname() {
        if ($this->Hostname != "")
            $result = $this->Hostname;
        elseif ($this->ServerVar('SERVER_NAME') != "")
            $result = $this->ServerVar('SERVER_NAME');
        else
            $result = "localhost.localdomain";

        return $result;
    }

    /**
     * Returns a message in the appropriate language.
     * @access private
     * @return string
     */
    function Lang($key) {
        if(count($this->language) < 1)
            $this->SetLanguage("en"); // set the default language
    
        if(isset($this->language[$key]))
            return $this->language[$key];
        else
            return "Language string failed to load: " . $key;
    }
    
    /**
     * Returns true if an error occurred.
     * @return bool
     */
    function IsError() {
        return ($this->error_count > 0);
    }

    /**
     * Changes every end of line from CR or LF to CRLF.  
     * @access private
     * @return string
     */
    function FixEOL($str) {
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        $str = str_replace("\n", $this->LE, $str);
        return $str;
    }

    /**
     * Adds a custom header. 
     * @return void
     */
    function AddCustomHeader($custom_header) {
        $this->CustomHeader[] = explode(":", $custom_header, 2);
    }
}

class sql_db
{
	var $connection;
	var $query_result;
	
	//var $num_queries = 0;
	//var $sql_queries = array();

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database)
	{
		global $debugbar;
		
		try {
			if (!in_array("mysql",PDO::getAvailableDrivers(),TRUE))
			{
				throw new PDOException ("PDO connection could not find driver mysql");
			}
		}
		catch (PDOException $pdoEx)
		{
			echo "Database Error: <br /> {$pdoEx->getMessage()}";
			exit;
		}

		try {
			
			if(defined( '_DEBUG' ) )
			{
				$this->connection = new DebugBar\DataCollector\PDO\TraceablePDO(new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true)));
				$debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($this->connection));
			}
			else
			{
				$this->connection = new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
			}
			
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			LogError($e->getMessage(), "", "connection open","PDO / sql_db");
			return false;
		}
	}
	
	function quote($value)
	{
		return $this->connection->quote($value);
	}

	function sql_close()
	{
		try {
			if($this->connection)
			{
				if($this->query_result)
				{
					$this->query_result->closeCursor();
				}
				$this->connection = null;
				return true;
			}
			else
			{
				return false;
			}
		} catch (PDOException $e) {
			LogError($e->getMessage(), "", "connection close","PDO / sql_close");
			return false;
		}

	}

	function sql_query($query = "", $args = "")
	{
		$query_result;
		/*if($this->query_result)
		{
			$this->query_result->closeCursor();
		}*/
		
		if($query != "")
		{
			//$this->num_queries++;
			//$this->sql_queries[] = $query;

			try { 
			if($args != "") 
			{
				$query_result = $this->connection->prepare($query); 
				$query_result->execute($args); 
			}
			else
			{
				$query_result = $this->connection->query($query);
			}
			
			//echo $query;
				
			//$sth = $pdh->query("SELECT * FROM sys.tables");
			//echo $this->query_result->fetchColumn();
				
			} catch(PDOExecption $e) { 
			echo $e->getMessage();
				LogError($e->getMessage(), "",$query, "PDO / sql_query");
			}
		}
		
		if($query_result)
		{
			$this->query_result = $query_result;
			return $this->query_result;
		}
		else
		{
			return false;
		}
	}

	function sql_numrows($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			
			if($query_id)
			{
				return $query_id->rowCount();
				//$num_rows = $res->fetchColumn();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numrows");
			return false;
		}
	}
	
	function sql_affectedrows()
	{
		try 
		{
			if($this->query_result)
			{
				return $this->query_result->rowCount();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_affectedrows");
			return false;
		}
	}
	
	function sql_numfields($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->columnCount();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numfields");
			return false;
		}
	}
	
	function sql_fieldname($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['name'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldname");
			return false;
		}
	}
	
	function sql_fieldtype($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['type'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldtype");
			return false;
		}
	}
	
	function sql_fetchrow($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$row = $query_id->fetch(PDO::FETCH_BOTH);
				//print_r($row);
				return $row;
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
	
	function sql_fetchrowset($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->fetchAll(PDO::FETCH_BOTH);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrowset");
			return false;
		}
		
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
	}
	
	/*function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	*/
	
	function sql_nextid()
	{
		try 
		{
			if($this->connection)
			{
				return $this->connection->lastInsertId();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_nextid");
			return false;
		}
	}
	
	function sql_freeresult($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
	
			if ( $query_id )
			{
				$query_id->closeCursor();
				return true;
			}
			else
			{
				return false;
			}
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_freeresult");
			return false;
		}
	}
	
	/*function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->connection);
		$result["code"] = @mysql_errno($this->connection);

		return $result;
	}
	*/
	function RowSelectorQuery($Statement)
	{
		$result = $this->sql_query($Statement);
		$dr = $this->sql_fetchrow($result);
		$this->sql_freeresult($result);
		return $dr;
	}
	
	function RowSelector($TableName = "", $PrimaryKeys, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " " . $key . "=" . ((bool)($QuotFields[$key]) ? "'" : "") . ($PrimaryKeys[$key]) . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
			
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
			}
	
			if($WhereStatement != "")
			{
				$Statement = "SELECT * FROM " . $TableName . $WhereStatement . " LIMIT 1 ";
			}
			
			if($Statement != "")
			{
				$result = $this->sql_query($Statement);
				$dr = $this->sql_fetchrow($result);
				$this->sql_freeresult($result);
				return $dr;
			}
		}
	}
	
	function ExecuteUpdater($TableName = "", $PrimaryKeys, $Collector, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
		}
	
		if($WhereStatement != "")
		{
			$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
		}
		
		if($WhereStatement != "")
		{
			$Statement = "UPDATE `" . $TableName . "` SET ";
			foreach($Collector as $key=>$val)
			{
				$Statement .= "`" . $key . "`=" . ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . $WhereStatement;
		}
		else
		{
			$Statement = "INSERT INTO `" . $TableName . "` (";
			foreach($Collector as $key=>$val)
			{
				$Statement .=  "`" . $key . "`,";
			}
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ") VALUES (";
	
			foreach($Collector as $key=>$val)
			{
				$Statement .= ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ")";
		}
	
		//echo $Statement;
		$this->sql_query($Statement);
	}

	function ExecuteDeleter($TableName = "", $PrimaryKeys , $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
	
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
				$Statement = "DELETE FROM `" . $TableName . "` " . $WhereStatement . " ";
				$this->sql_query($Statement);
				
				//echo $Statement;
			}
		}
	}
	
	function ExecuteScalar($query_id = 0, $columnIndex = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				//print_r($row);
				return $query_id->fetchColumn($columnIndex);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
}
	function randomCode($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	function randomPin($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '0123456789';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	
	function SendMail($Body, $Subject = "", $To="")
	{
		$MailContent = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
		$MailContent .= "<html>";
		$MailContent .= "<head>";
		$MailContent .= "<style type='text/css'>";
			$MailContent .= ".n {font-family: Verdana, Arial;font-size: 12px;}";
			$MailContent .= ".ns {font-family: Verdana, Arial;font-size: 9px;}";
			$MailContent .= ".h {font-family: Verdana, Arial;font-size: 12px;font-weight: bold;color: #000099;}";
			$MailContent .= ".tbl {background-color:  #dddddd;}";
			$MailContent .= ".c {background-color:  #f1f1f1;}";
		$MailContent .= "</style>";
		$MailContent .= "</head>";
		$MailContent .= "<body class='n'>";

		$MailContent .= $Body;

		$MailContent .= "</body>";
		$MailContent .= "</html>";	

		//global $config;
		
		$mailServer="localhost";

		$mail = new PHPMailer();
		$mail->IsMail();
		$mail->Host     = $mailServer;
		$mail->SMTPAuth = false;
		$mail->CharSet = "UTF-8";	
		$mail->From     = $contactMail;
		$mail->FromName = site_title;
		$mail->Subject  = $Subject != "" ? $Subject : site_title;
		$mail->Body     = $MailContent;
		$To = ($To != "" ? $To : $contactMail);
		$mail->AddAddress($To);	
		$mail->IsHTML(true);
		if(!$mail->Send())
		{
			LogError("Error during send mail","to: " . $To,$mail->ErrorInfo,"PHP");
		}
	}

	$host = 'localhost';
	$database = 'panelwear4safe_eu_db';
	$dbuser = 'panel.wear4safe_eu_db_dbuser';

	if(isset($_GET['func']) && $_GET['func']=='checkUser') {
		//https://panel.wear4safe.eu/api/api.php?func=checkUser&email=info@e-trikala.gr&pass=12345678
		//https://panel.wear4safe.eu/api/api.php?func=checkUser&email=epoptis1@e-trikala.gr&pass=12345
		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);

		$data = array();
		$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_name='".$_REQUEST['email']."' AND user_password='".$_REQUEST['pass']."' LIMIT 1");
		
		if(intval($rowUser['user_id'])>0) {
			$user_id = $rowUser['user_id'];
			
			//$random=randomCode(8);
			//$updateQuery="UPDATE users SET randomcode='".$random."' WHERE user_id='".$user_id."'";
			//$resultUpdate = $db->sql_query($updateQuery);
			
			//$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_name='".$_REQUEST['email']."' AND user_password='".$_REQUEST['pass']."' LIMIT 1");
			//if(intval($rowUser['user_id'])>0){
				
				$dataDetails = array();
				$dataDetailsEmployees = array();
				//echo "SELECT * FROM organizations WHERE organization_id=".$rowUser['organization_id'];
				$orgRow=$db->RowSelectorQuery("SELECT * FROM organizations WHERE organization_id=".$rowUser['organization_id']);
				//$varsArr=explode(",",$orgRow["sensors"]);
				
				$querySensors="SELECT * FROM sensors WHERE is_valid='True' AND organization_id = '".$rowUser['organization_id']."'";	
				$resultSensors = $db->sql_query($querySensors);
				while ($rowSensors = $db->sql_fetchrow($resultSensors)){
					$drData = $db->RowSelectorQuery("SELECT * FROM data WHERE sensor_id='".$rowSensors['sensor_id']."' ORDER BY alert_id DESC LIMIT 1");
					$arr1=array();$arr2=array();$arr3=array();$arr4=array();$arr5=array();
					
					$pro=$rowSensors['profession_id'];
					$org=$rowSensors['organization_id'];
					$map = $db->RowSelectorQuery("SELECT * FROM prosettings WHERE profession_id='".$pro."' AND organization_id='".$org."' LIMIT 1");

					// Sensor1
					if(intval($rowSensors['sensor1_maptype_id'])>0){
						$map1_id =  $rowSensors['sensor1_map_id'];
						$maptype1_id =  $rowSensors['sensor1_maptype_id'];
						$row1 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor1_maptype_id']."'");
						$map1_name = $row1['map_name'];
						$maptype1_name = $row1['maptype_name'];
						
						if(intval($map['condition1_id'])>0){
						//if(intval($row1['condition_id'])>0) {
							//$cond1 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row1['condition_id']."'");
							$cond1 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition1_id']."'");
							$condition1_id=$cond1['condition_id'];
							$condition1_name=$cond1['condition_name'];
						}
					} else {
						$map1_id=0;
						$maptype1_id=0;
						$map1_name="";
						$maptype1_name="";
						$condition1_id=0;
						$condition1_name="";
					}
					array_push($arr1,array(
						'id' => $rowSensors['sensor1_name'],
						'map1_id' => $map1_id,
						'maptype1_id' => $maptype1_id,
						'map1_name' => $map1_name,
						'maptype1_name' => $maptype1_name,
						//'mandatory' => ($row1['mandatory']=='True'?'True':'False'),
						'mandatory' => ($map['mandatory1']=='True'?'True':'False'),
						'condition1_id' => $condition1_id,
						'condition1_name' => $condition1_name
					));
					
					
					// Sensor2
					if(intval($rowSensors['sensor2_maptype_id'])>0){
						$map2_id =  $rowSensors['sensor2_map_id'];
						$maptype2_id =  $rowSensors['sensor2_maptype_id'];
						$row2 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor2_maptype_id']."'");
						$map2_name = $row2['map_name'];
						$maptype2_name = $row2['maptype_name'];
						
						if(intval($map['condition2_id'])>0){
						//if(intval($row2['condition_id'])>0) {
							//$cond2 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row2['condition_id']."'");
							$cond2 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition2_id']."'");
							$condition2_id=$cond2['condition_id'];
							$condition2_name=$cond2['condition_name'];
						}
					} else {
						$map2_id=0;
						$maptype2_id=0;
						$map2_name="";
						$maptype2_name="";
					}
					array_push($arr2,array(
						'id' => $rowSensors['sensor2_name'],
						'map2_id' => $map2_id,
						'maptype2_id' => $maptype2_id,
						'map2_name' => $map2_name,
						'maptype2_name' => $maptype2_name,
						//'mandatory' => ($row2['mandatory']=='True'?'True':'False'),
						'mandatory' => ($map['mandatory2']=='True'?'True':'False'),
						'condition2_id' => $condition2_id,
						'condition2_name' => $condition2_name
					));
					
					// Sensor3
					if(intval($rowSensors['sensor3_maptype_id'])>0){
						$map3_id =  $rowSensors['sensor3_map_id'];
						$maptype3_id =  $rowSensors['sensor3_maptype_id'];
						$row3 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor3_maptype_id']."'");
						$map3_name = $row3['map_name'];
						$maptype3_name = $row3['maptype_name'];
						
						if(intval($map['condition3_id'])>0){
						//if(intval($row3['condition_id'])>0) {
							//$cond3 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row3['condition_id']."'");
							$cond3 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition3_id']."'");
							$condition3_id=$cond3['condition_id'];
							$condition3_name=$cond3['condition_name'];
						}
					} else {
						$map3_id=0;
						$maptype3_id=0;
						$map3_name="";
						$maptype3_name="";
					}
					array_push($arr3,array(
						'id' => $rowSensors['sensor3_name'],
						'map3_id' => $map3_id,
						'maptype3_id' => $maptype3_id,
						'map3_name' => $map3_name,
						'maptype3_name' => $maptype3_name,
						//'mandatory' => ($row3['mandatory']=='True'?'True':'False'),
						'mandatory' => ($map['mandatory3']=='True'?'True':'False'),
						'condition3_id' => $condition3_id,
						'condition3_name' => $condition3_name
					));
					
					// Sensor4
					if(intval($rowSensors['sensor4_maptype_id'])>0){
						$map4_id =  $rowSensors['sensor4_map_id'];
						$maptype4_id =  $rowSensors['sensor4_maptype_id'];
						$row4 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor4_maptype_id']."'");
						$map4_name = $row4['map_name'];
						$maptype4_name = $row4['maptype_name'];
						
						if(intval($map['condition4_id'])>0){
						//if(intval($row4['condition_id'])>0) {
							//$cond4 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row4['condition_id']."'");
							$cond4 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition4_id']."'");
							$condition4_id=$cond4['condition_id'];
							$condition4_name=$cond4['condition_name'];
						}
					} else {
						$map4_id=0;
						$maptype4_id=0;
						$map4_name="";
						$maptype4_name="";
					}
					array_push($arr4,array(
						'id' => $rowSensors['sensor4_name'],
						'map4_id' => $map4_id,
						'maptype4_id' => $maptype4_id,
						'map4_name' => $map4_name,
						'maptype4_name' => $maptype4_name,
						//'mandatory' => ($row4['mandatory']=='True'?'True':'False'),
						'mandatory' => ($map['mandatory4']=='True'?'True':'False'),
						'condition4_id' => $condition4_id,
						'condition4_name' => $condition4_name
					));
					
					
					// Sensor5
					if(intval($rowSensors['sensor5_maptype_id'])>0){
						$map5_id =  $rowSensors['sensor5_map_id'];
						$maptype5_id =  $rowSensors['sensor5_maptype_id'];
						$row5 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor5_maptype_id']."'");
						$map5_name = $row5['map_name'];
						$maptype5_name = $row5['maptype_name'];
						
						if(intval($map['condition5_id'])>0){
						//if(intval($row5['condition_id'])>0) {
							//$cond5 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row5['condition_id']."'");
							$cond5 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition5_id']."'");
							$condition5_id=$cond5['condition_id'];
							$condition5_name=$cond5['condition_name'];
						}
					} else {
						$map5_id=0;
						$maptype5_id=0;
						$map5_name="";
						$maptype5_name="";
					}
					array_push($arr5,array(
						'id' => $rowSensors['sensor5_name'],
						'map5_id' => $map5_id,
						'maptype5_id' => $maptype5_id,
						'map5_name' => $map5_name,
						'maptype5_name' => $maptype5_name,
						//'mandatory' => ($row5['mandatory']=='True'?'True':'False'),
						'mandatory' => ($map['mandatory5']=='True'?'True':'False'),
						'condition5_id' => $condition5_id,
						'condition5_name' => $condition5_name
					));
					
					array_push($dataDetails,array(
						'sensor_id' => $rowSensors['sensor_id'],
						'sensor_name' => $rowSensors['sensor_name'],
						'active' => (time() - strtotime($rowSensors['lastupdate'])>86400?'False':'True'),
						'imei' => $rowSensors['imei'],
						'profession_id' => $rowSensors['profession_id'],
						'lat' => ($drData['lat']==''?'40.80702':$drData['lat']),
						'lng' => ($drData['lng']==''?'22.053343':$drData['lng']),
						'probe_1' => $arr1, //$rowSensors['sensor1_name'],
						'probe_2' => $arr2, //$rowSensors['sensor2_name'],
						'probe_3' => $arr3, //$rowSensors['sensor3_name'],
						'probe_4' => $arr4, //$rowSensors['sensor4_name'],
						'probe_5' => $arr5 //$rowSensors['sensor5_name']
						)
					);	
				}
				
				$filterEmployees = (intval($rowUser['sector_id'])>0?" AND sector_id=".$rowUser['sector_id']:"");
				$queryEmployees="SELECT * FROM employees WHERE 1=1 ".$filterEmployees." AND is_valid='True' AND organization_id = '".$rowUser['organization_id']."'";	
			
				$resultEmployees = $db->sql_query($queryEmployees);
				while ($rowEmployees = $db->sql_fetchrow($resultEmployees)){
					$drProfession = $db->RowSelectorQuery("SELECT * FROM professions WHERE profession_id=".$rowEmployees['profession_id']);
					array_push($dataDetailsEmployees,array(
						'employee_id' => $rowEmployees['employee_id'],
						'firstname' => $rowEmployees['firstname'],
						'surname' => $rowEmployees['surname'],
						'phone' => $rowEmployees['phone'],
						'email' => $rowEmployees['email'],
						'profession_id' => $rowEmployees['profession_id'],
						'sector_id' => $rowEmployees['sector_id'],
						'profession_name' => $drProfession['profession_name'],
						'pin' => str_pad(strval($rowEmployees["employee_id"]),4,'0',STR_PAD_LEFT)
						)
					);	
				}
					
				array_push($data,array(
					'id' => $rowUser['user_id'],
					'organization_id' => $rowUser['organization_id'],
					'sector_id' => $rowUser['sector_id'],
					'organization_name' => $orgRow['organization_name'],
					'sensors' => $dataDetails,
					'employees' => $dataDetailsEmployees,
					)
				);
			//}
			//array_push($data,array(
			//	'id' => $rowUser['user_id'],
			//	'organizationid' => $rowUser['user_id'],
			//	'code' => $rowUser['randomcode']
			//	)
			//);
			//file_put_contents('log.html', "ok".$rowUser['user_id'], FILE_APPEND);
			$json = json_encode($data);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
			//print_r($json); 
		} else {
			$data = array();
			array_push($data,array(
				'id' => '0',
				'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
		
	} else 	if(isset($_GET['func']) && $_GET['func']=='problem') {	
		//https://panel.wear4safe.eu/api/api.php?func=problem&employee=0016&sensor=25&maptype=12&type=1
		$employee_id = intval($_GET['employee']);
		$sensor_id = intval($_GET['sensor']);
		$maptype_id = intval($_GET['maptype']);
		$type_id = intval($_GET['type']);
		if($employee_id>0 && $sensor_id>0 && $maptype_id>0 && $type_id>0){
			$db = new sql_db($host, $dbuser, $dbpass, $database, false);
			$insertQuery = "INSERT INTO problems(employee_id, sensor_id, maptype_id, type_id, date_insert) VALUES ('".$employee_id."','".$sensor_id."','".$maptype_id."','".$type_id."','".date('Y-m-d H:i:s')."')";
			$result = $db->sql_query($insertQuery);
			$data = array();
			array_push($data,array(
					'error' => '0',
					'message' => 'Η ανάθεση ολοκληρώθηκε'
				)
			);
			$json = json_encode($data);
			//$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			//print_r($json);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
			exit;
		} else {
			$data = array();
			array_push($data,array(
				'error' => '1',
				'message' => 'Προέκυψαν σφάλματα! '
				)
			);
			$json = json_encode($data);
			//$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			//print_r($json);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
			exit;
		}


		
		
	} else 	if(isset($_GET['func']) && $_GET['func']=='employee') {
		//https://panel.wear4safe.eu/api/api.php?func=employee&pin=0016
		//https://panel.wear4safe.eu/api/api.php?func=employee&pin=0143
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$pin = intval($_GET['pin']);
		
		$weather =  file_get_contents("");
		$wArr = json_decode($weather,true);

		//echo $wArr['current']['weather'][0]['id'].'<br>';
		//echo $wArr['current']['temp'].'<br>';
		//echo $wArr['current']['humidity'].'<br>';
		//echo $wArr['current']['wind_speed'].'<br>';
		
		if($wArr['current']['weather'][0]['id']>=200 && $wArr['current']['weather'][0]['id']<600) $rain=1;
		if($wArr['current']['weather'][0]['id']>=600 && $wArr['current']['weather'][0]['id']<650) $snow=1;
		if(intval($wArr['current']['temp'])>35) $heat=1;
		if(intval($wArr['current']['temp'])<5) $cold=1;
		
		//1=Χιόνι, 2=Βροχή, 3=Παγετός, 4=Καύσωνας
		//$rain=1;

		//Αν δεν υπάρχει ο εργαζόμενος επέστρεψε 0
		$emplRow = $db->RowSelectorQuery("SELECT * FROM employees WHERE employee_id = '".intval($pin)."'");
		if(intval($emplRow['employee_id'])==0){
			$data = array();
			array_push($data,array(
				'id' => '0',
				'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			print_r($json);
			exit;
		}
		//βρες την ανάθεση
		$assignment = $db->RowSelectorQuery("SELECT * FROM assignments WHERE employee_id=".intval($pin));
		if(intval($assignment['assignment_id'])>0){
			//while ($rowSensors = $db->sql_fetchrow($resultSensors)){
			$rowSensors=$db->RowSelectorQuery("SELECT * FROM sensors WHERE sensor_id = '".$assignment['sensor_id']."' ORDER BY date_insert DESC LIMIT 1");
				$arr1=array();$arr2=array();$arr3=array();$arr4=array();$arr5=array();
				
				$pro=$rowSensors['profession_id'];
				$org=$rowSensors['organization_id'];
				$map = $db->RowSelectorQuery("SELECT * FROM prosettings WHERE profession_id='".$pro."' AND organization_id='".$org."' LIMIT 1");

				if(intval($rowSensors['sensor1_maptype_id'])>0){
					$map1_id =  $rowSensors['sensor1_map_id'];
					$maptype1_id =  $rowSensors['sensor1_maptype_id'];
					$row1 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor1_maptype_id']."'");
					$map1_name = $row1['map_name'];
					$maptype1_name = $row1['maptype_name'];
					
					$display1='True';
					$current_condition1='Υποχρεωτική χρήση';
					//if($row1['mandatory']=='False') $current_condition1='Προαιρετική χρήση';
					if($map['mandatory1']=='False') $current_condition1='Προαιρετική χρήση';
			
					//if(intval($row1['condition_id'])>0) {
					if(intval($map['condition1_id'])>0){
						//$cond1 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row1['condition_id']."'");
						$cond1 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition1_id']."'");
						$condition1_id=$cond1['condition_id'];
						$condition1_name=$cond1['condition_name'];
						$maintenance1 = $row1['maintenance'];
						$storage1 = $row1['storage'];
						
						if($map['mandatory1']=='True'){
							$current_condition1='Υποχρεωτική χρήση';
							$display1='True';
						} else if (intval($map['condition1_id'])>0){
							if(intval($map['condition1_id'])==1 && $show==1){
								$current_condition1='Χιόνι';
								$display1='True';
							} else if(intval($map['condition1_id'])==2 && $rain==1){
								$current_condition1='Βροχή';
								$display1='True';
							} if(intval($map['condition1_id'])==3 && $cold==1){
								$current_condition1='Παγετός';
								$display1='True';
							} if(intval($map['condition1_id'])==4 && $heat==1){
								$current_condition1='Καύσωνας';
								$display1='True';
							} else {
								$current_condition1='Φυσιολογικές συνθήκες';
								$display1='False';							
							}
						}

					}
				} else {
					$map1_id=0;
					$maptype1_id=0;
					$map1_name="";
					$maptype1_name="";
					$condition1_id=0;
					$condition1_name="";
					$maintenance1="";
					$storage1 = "";
					$show1='False';
				}
				array_push($arr1,array(
					'id' => $rowSensors['sensor1_name'],
					'map1_id' => $map1_id,
					'maptype1_id' => $maptype1_id,
					'map1_name' => $map1_name,
					'maptype1_name' => $maptype1_name,
					//'mandatory' => ($row1['mandatory']=='True'?'True':'False'),
					'mandatory' => ($map['mandatory1']=='True'?'True':'False'),
					'condition1_id' => $condition1_id,
					'condition1_name' => $condition1_name,
					'maintenance1' => $maintenance1,
					'storage1' => $storage1,
					'current_condition1' => $current_condition1,
					'display1' => $display1,
				));
				
				if(intval($rowSensors['sensor2_maptype_id'])>0){
					$map2_id =  $rowSensors['sensor2_map_id'];
					$maptype2_id =  $rowSensors['sensor2_maptype_id'];
					$row2 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor2_maptype_id']."'");
					$map2_name = $row2['map_name'];
					$maptype2_name = $row2['maptype_name'];
					
					$display2='True';
					$current_condition2='Υποχρεωτική χρήση';
					
					//if($row2['mandatory']=='False') $current_condition2='Προαιρετική χρήση';
					if($map['mandatory2']=='False') $current_condition2='Προαιρετική χρήση';
					
					if(intval($map['condition2_id'])>0){
					//if(intval($row2['condition_id'])>0) {
						$cond2 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition2_id']."'");
						//$cond2 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row2['condition_id']."'");
						$condition2_id=$cond2['condition_id'];
						$condition2_name=$cond2['condition_name'];
						$maintenance2 = $row2['maintenance'];
						$storage2 = $row2['storage'];
						
						if($map['mandatory2']=='True'){
							$current_condition2='Υποχρεωτική χρήση';
							$display2='True';
						} else if (intval($map['condition2_id'])>0){
							if(intval($map['condition2_id'])==1 && $show==1){
								$current_condition2='Χιόνι';
								$display2='True';
							} else if(intval($map['condition2_id'])==2 && $rain==1){
								$current_condition2='Βροχή';
								$display2='True';
							} if(intval($map['condition2_id'])==3 && $cold==1){
								$current_condition2='Παγετός';
								$display2='True';
							} if(intval($map['condition2_id'])==4 && $heat==1){
								$current_condition2='Καύσωνας';
								$display2='True';
							} else {
								$current_condition2='Φυσιολογικές συνθήκες';
								$display2='False';							
							}
						}
					}
				} else {
					$map2_id=0;
					$maptype2_id=0;
					$map2_name="";
					$maptype2_name="";
					$maintenance2="";
					$storage2 = "";
				}
				array_push($arr2,array(
					'id' => $rowSensors['sensor2_name'],
					'map2_id' => $map2_id,
					'maptype2_id' => $maptype2_id,
					'map2_name' => $map2_name,
					'maptype2_name' => $maptype2_name,
					//'mandatory' => ($row2['mandatory']=='True'?'True':'False'),
					'mandatory' => ($map['mandatory2']=='True'?'True':'False'),
					'condition2_id' => $condition2_id,
					'condition2_name' => $condition2_name,
					'maintenance2' => $maintenance2,
					'storage2' => $storage2,
					'current_condition2' => $current_condition2,
					'display2' => $display2,
				));
				
				
				if(intval($rowSensors['sensor3_maptype_id'])>0){
					$map3_id =  $rowSensors['sensor3_map_id'];
					$maptype3_id =  $rowSensors['sensor3_maptype_id'];
					$row3 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor3_maptype_id']."'");
					$map3_name = $row3['map_name'];
					$maptype3_name = $row3['maptype_name'];
					
					$display3='True';
					$current_condition3='Υποχρεωτική χρήση';
					
					//if($row3['mandatory']=='False') $current_condition3='Προαιρετική χρήση';
					if($map['mandatory3']=='False') $current_condition3='Προαιρετική χρήση';

					//if(intval($row3['condition_id'])>0) {
					if(intval($map['condition3_id'])>0){
						//$cond3 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row3['condition_id']."'");
						$cond3 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition3_id']."'");
						$condition3_id=$cond3['condition_id'];
						$condition3_name=$cond3['condition_name'];
						$maintenance3 = $row3['maintenance'];
						$storage3 = $row3['storage'];
						
						if($map['mandatory3']=='True'){
							$current_condition3='Υποχρεωτική χρήση';
							$display3='True';
						} else if (intval($map['condition3_id'])>0){
							if(intval($map['condition3_id'])==1 && $show==1){
								$current_condition3='Χιόνι';
								$display3='True';
							} else if(intval($map['condition3_id'])==2 && $rain==1){
								$current_condition3='Βροχή';
								$display3='True';
							} if(intval($map['condition3_id'])==3 && $cold==1){
								$current_condition3='Παγετός';
								$display3='True';
							} if(intval($map['condition3_id'])==4 && $heat==1){
								$current_condition3='Καύσωνας';
								$display3='True';
							} else {
								$current_condition3='Φυσιολογικές συνθήκες';
								$display3='False';							
							}
						}
					}
				} else {
					$map3_id=0;
					$maptype3_id=0;
					$map3_name="";
					$maptype3_name="";
					$maintenance3="";
					$storage3 = "";
				}
				array_push($arr3,array(
					'id' => $rowSensors['sensor3_name'],
					'map3_id' => $map3_id,
					'maptype3_id' => $maptype3_id,
					'map3_name' => $map3_name,
					'maptype3_name' => $maptype3_name,
					//'mandatory' => ($row3['mandatory']=='True'?'True':'False'),
					'mandatory' => ($map['mandatory3']=='True'?'True':'False'),
					'condition3_id' => $condition3_id,
					'condition3_name' => $condition3_name,
					'maintenance3' => $maintenance3,
					'storage3' => $storage3,
					'current_condition3' => $current_condition3,
					'display3' => $display3,
				));
				
				
				if(intval($rowSensors['sensor4_maptype_id'])>0){
					$map4_id =  $rowSensors['sensor4_map_id'];
					$maptype4_id =  $rowSensors['sensor4_maptype_id'];
					$row4 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor4_maptype_id']."'");
					$map4_name = $row4['map_name'];
					$maptype4_name = $row4['maptype_name'];
					
					$display4='True';
					$current_condition4='Υποχρεωτική χρήση';
					
					//if($row4['mandatory']=='False') $current_condition4='Προαιρετική χρήση';
					if($map['mandatory4']=='False') $current_condition4='Προαιρετική χρήση';
					
					$display5='True';
					$current_condition5='Υποχρεωτική χρήση';
			
					//if(intval($row4['condition_id'])>0) {
					if(intval($map['condition4_id'])>0){
						//$cond4 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row4['condition_id']."'");
						$cond4 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition4_id']."'");
						$condition4_id=$cond4['condition_id'];
						$condition4_name=$cond4['condition_name'];
						$maintenance4 = $row4['maintenance'];
						$storage4 = $row4['storage'];
						
						if($map['mandatory4']=='True'){
							$current_condition4='Υποχρεωτική χρήση';
							$display4='True';
						} else if (intval($map['condition4_id'])>0){
							if(intval($map['condition4_id'])==1 && $show==1){
								$current_condition4='Χιόνι';
								$display4='True';
							} else if(intval($map['condition4_id'])==2 && $rain==1){
								$current_condition4='Βροχή';
								$display4='True';
							} if(intval($map['condition4_id'])==3 && $cold==1){
								$current_condition4='Παγετός';
								$display4='True';
							} if(intval($map['condition4_id'])==4 && $heat==1){
								$current_condition4='Καύσωνας';
								$display4='True';
							} else {
								$current_condition4='Φυσιολογικές συνθήκες';
								$display4='False';				
							}
						}
					}
				} else {
					$map4_id=0;
					$maptype4_id=0;
					$map4_name="";
					$maptype4_name="";
					$maintenance4="";
					$storage4 = "";
				}
				

				array_push($arr4,array(
					'id' => $rowSensors['sensor4_name'],
					'map4_id' => $map4_id,
					'maptype4_id' => $maptype4_id,
					'map4_name' => $map4_name,
					'maptype4_name' => $maptype4_name,
					//'mandatory' => ($row4['mandatory']=='True'?'True':'False'),
					'mandatory' => ($map['mandatory4']=='True'?'True':'False'),
					'condition4_id' => $condition4_id,
					'condition4_name' => $condition4_name,
					'maintenance4' => $maintenance4,
					'storage4' => $storage4,
					'current_condition4' => $current_condition4,
					'display4' => $display4,
				));
				
				
				
				if(intval($rowSensors['sensor5_maptype_id'])>0){
					$map5_id =  $rowSensors['sensor5_map_id'];
					$maptype5_id =  $rowSensors['sensor5_maptype_id'];
					$row5 = $db->RowSelectorQuery("SELECT * FROM maptypes t1 INNER JOIN map t2 ON t1.map_id=t2.map_id WHERE maptype_id='".$rowSensors['sensor5_maptype_id']."'");
					$map5_name = $row5['map_name'];
					$maptype5_name = $row5['maptype_name'];
					
					$display5='True';
					$current_condition5='Υποχρεωτική χρήση';
					
					//if($row5['mandatory']=='False') $current_condition5='Προαιρετική χρήση';
					if($map['mandatory5']=='False') $current_condition5='Προαιρετική χρήση';
				
					//if(intval($row5['condition_id'])>0) {
					if(intval($map['condition5_id'])>0){
						//$cond5 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$row5['condition_id']."'");
						$cond5 = $db->RowSelectorQuery("SELECT * FROM conditions WHERE condition_id='".$map['condition5_id']."'");
						$condition5_id=$cond5['condition_id'];
						$condition5_name=$cond5['condition_name'];
						$maintenance5 = $row5['maintenance'];
						$storage5 = $row5['storage'];
						
						if($map['mandatory5']=='True'){
							$current_condition5='Υποχρεωτική χρήση';
							$display5='True';
						} else if (intval($map['condition5_id'])>0){
							if(intval($map['condition5_id'])==1 && $show==1){
								$current_condition5='Χιόνι';
								$display5='True';
							} else if(intval($map['condition5_id'])==2 && $rain==1){
								$current_condition5='Βροχή';
								$display5='True';
							} if(intval($map['condition5_id'])==3 && $cold==1){
								$current_condition5='Παγετός';
								$display5='True';
							} if(intval($map['condition5_id'])==4 && $heat==1){
								$current_condition5='Καύσωνας';
								$display5='True';
							} else {
								$current_condition5='Φυσιολογικές συνθήκες';
								$display5='False';							
							}
						}
					}
				} else {
					$map5_id=0;
					$maptype5_id=0;
					$map5_name="";
					$maptype5_name="";
					$maintenance5="";
					$storage5 = "";
				}
				array_push($arr5,array(
					'id' => $rowSensors['sensor5_name'],
					'map5_id' => $map5_id,
					'maptype5_id' => $maptype5_id,
					'map5_name' => $map5_name,
					'maptype5_name' => $maptype5_name,
					//'mandatory' => ($row5['mandatory']=='True'?'True':'False'),
					'mandatory' => ($map['mandatory5']=='True'?'True':'False'),
					'condition5_id' => $condition5_id,
					'condition5_name' => $condition5_name,
					'maintenance5' => $maintenance5,
					'storage5' => $storage5,
					'current_condition5' => $current_condition5,
					'display5' => $display5,
				));
				
				array_push($data,array(
					'sensor_id' => $rowSensors['sensor_id'],
					'sensor_name' => $rowSensors['sensor_name'],
					'imei' => $rowSensors['imei'],
					'profession_id' => $rowSensors['profession_id'],
					//'lat' => $drData['lat'],
					//'lng' => $drData['lng'],
					'probe_1' => $arr1, //$rowSensors['sensor1_name'],
					'probe_2' => $arr2, //$rowSensors['sensor2_name'],
					'probe_3' => $arr3, //$rowSensors['sensor3_name'],
					'probe_4' => $arr4, //$rowSensors['sensor4_name'],
					'probe_5' => $arr5 //$rowSensors['sensor5_name']
					)
				);	
			//}
				
			$json = json_encode($data);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'id' => '-1',
				'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			print_r($json);
		}


	} else 	if(isset($_GET['func']) && $_GET['func']=='weather') {
		$organization_id=$_GET['organization_id'];
		//https://panel.wear4safe.eu/api/api.php?func=weather
		//$weather =  file_get_contents("https://api.openweathermap.org/data/2.5/onecall?lat=40.5785429&lon=22.9594983&appid=c6dd5752ed096e87331429306f36c360&units=metric&lang=el");
		$weather =  file_get_contents("");
		echo $weather;
	} else 	if(isset($_GET['func']) && $_GET['func']=='sensors') {
		//https://panel.wear4safe.eu/api/api.php?func=sensors
		//https://panel.wear4safe.eu/api/api.php?func=sensors&organization=1
		//https://panel.wear4safe.eu/api/api.php?func=sensors&organization=1&profession=5
		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
		$organization_id = intval($_GET['organization']);
		$profession_id = intval($_GET['profession']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		//$query="SELECT DISTINCT imei,sensor_id,sensor_name FROM sensors WHERE is_valid='True'";	
		
		$filter=(intval($profession_id)>0?" AND profession_id=".$profession_id:"");
		$query="SELECT * FROM sensors WHERE 1=1 ".$filter." AND is_valid='True' ".($organization_id>0?" AND organization_id='".$organization_id."'":"");	
		$results = $db->sql_query($query);
		$counter=0;
		
		while ($rowSensors = $db->sql_fetchrow($results)){
			array_push($data,array(
				'sensor_id' => $rowSensors['sensor_id'],
				'sensor_name' => $rowSensors['sensor_name'],
				'imei' => $rowSensors['imei'],
				'profession_id' => $rowSensors['profession_id'],
				'probe_1' => $rowSensors['sensor1_name'],
				'probe_2' => $rowSensors['sensor2_name'],
				'probe_3' => $rowSensors['sensor3_name'],
				'probe_4' => $rowSensors['sensor4_name'],
				'probe_5' => $rowSensors['sensor5_name'],
				'lastupdate' => $rowSensors['lastupdate']
				)
			);	
		}
		
		if(sizeof($data)>0){
			$json = json_encode($data);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				 'sensor_id' => '0',
				//'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}	
	} else 	if(isset($_GET['func']) && $_GET['func']=='professionmap') {
		//https://panel.wear4safe.eu/api/api.php?func=professionmap
		//https://panel.wear4safe.eu/api/api.php?func=professionmap&profession=1
		$profession_id = intval($_GET['profession']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		$query="SELECT t1.professionsmap_id,t1.is_valid,t3.map_name,t2.maptype_name FROM professionsmap t1 INNER JOIN maptypes t2 ON t1.maptype_id=t2.maptype_id 
									INNER JOIN map t3 ON t2.map_id=t3.map_id WHERE 1=1 AND t1.is_valid='True' ".($profession_id>0?" AND profession_id='".$profession_id."'":"");	

		$results = $db->sql_query($query);
		$counter=0;
		
		while ($rowProfessionMap = $db->sql_fetchrow($results)){
			array_push($data,array(
				'professionsmap_id' => $rowProfessionMap['professionsmap_id'],
				'map_name' => $rowProfessionMap['map_name'],
				'maptype_name' => $rowProfessionMap['maptype_name']
				)
			);	
		}
		
		if(sizeof($data)>0){
			$json = json_encode($data);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'condition_id' => '0',
				//'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else 	if(isset($_GET['func']) && $_GET['func']=='assignments') {
		//https://panel.wear4safe.eu/api/api.php?func=assignments
		//https://panel.wear4safe.eu/api/api.php?func=assignments&user=1
		//https://panel.wear4safe.eu/api/api.php?func=assignments&user=16
		//https://panel.wear4safe.eu/api/api.php?func=assignments&employee=138
		//https://panel.wear4safe.eu/api/api.php?func=assignments&employee=136
		//https://panel.wear4safe.eu/api/api.php?func=assignments&user=1&employee=136
		//https://panel.wear4safe.eu/api/api.php?func=assignments&organization=1
		//https://panel.wear4safe.eu/api/api.php?func=assignments&sector=1
		$user_id = intval($_GET['user']);
		$employee_id = intval($_GET['employee']);
		$organization_id = intval($_GET['organization']);
		$sector_id = intval($_GET['sector']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		$filter="";
		$filter.=($user_id>0?" AND t1.user_id=".$user_id." ":"");
		$filter.=($employee_id>0?" AND t1.employee_id=".$employee_id." ":"");
		$filter.=($organization_id>0?" AND t1.user_id IN (SELECT user_id FROM users WHERE organization_id=".$organization_id.") ":"");
		$filter.=($sector_id>0?" AND t3.sector_id=".$sector_id." ":"");
		
		$query = "SELECT t1.*,t2.user_fullname, t3.surname,t3.firstname,t4.sensor_name,t4.imei
		FROM assignments t1 
		INNER JOIN users t2 ON t1.user_id=t2.user_id 
		INNER JOIN employees t3 ON t1.employee_id=t3.employee_id 
		INNER JOIN sensors t4 ON t1.sensor_id=t4.sensor_id 
		WHERE 1=1 ".$filter." ORDER BY t1.date_insert DESC";
		
		//echo $query;		
								
		$results = $db->sql_query($query);
		$counter=0;
		
		while ($rowAssignments = $db->sql_fetchrow($results)){
			array_push($data,array(
				'assignment_id' => $rowAssignments['assignment_id'],
				'sensor_id' => $rowAssignments['sensor_id'],
				'employee_id' => $rowAssignments['employee_id'],
				'user_fullname' => $rowAssignments['user_fullname'],
				'employee_fullname' => $rowAssignments["surname"].' '.$rowAssignments["firstname"],
				'sensor_name' => $rowAssignments['sensor_name'],
				'imei' => $rowAssignments['imei'],
				'assignment_date' => $rowAssignments['assignment_date'],
				'return_date' => $rowAssignments['return_date'],
				)
			);	
		}
		
		if(sizeof($data)>0){
			$json = json_encode($data);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'assignment_id' => '0',
				//'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
		
	} else 	if(isset($_GET['func']) && $_GET['func']=='addassignment') {
		//https://panel.wear4safe.eu/api/api.php?func=addassignment&user=16&sensor=26&employee=138
		//https://panel.wear4safe.eu/api/api.php?func=addassignment&user=16&sensor=25&employee=136
		$message="";
		$error=0;
		$user_id = intval($_GET['user']);
		$employee_id = intval($_GET['employee']);
		$sensor_id = intval($_GET['sensor']);
		
		if(intval($user_id)==0 ) $error=1;
		if(intval($employee_id)==0 ) $error=1;
		if(intval($sensor_id)==0 ) $error=1;
				
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		//τσεκ αν ο αισθητήρας είναι διαθέσιμος
		$drSensor = $db->RowSelectorQuery("SELECT * FROM assignments WHERE sensor_id='".$sensor_id."' ORDER BY date_insert DESC limit 1");

		if(intval(strlen($drSensor['return_date']))<8 && intval($drSensor['assignment_id'])>0){
			$error=1;
			$message.=" (O αισθητήρας είναι ήδη σε ανάθεση) ";
		}
		
		//τσεκ αν ο χρήστης έχει επιστρέψει τον προηγούμενο εξοπλισμό
		$drSensor = $db->RowSelectorQuery("SELECT * FROM assignments WHERE employee_id='".$employee_id."' ORDER BY date_insert DESC limit 1");
		if(intval(strlen($drSensor['return_date']))<8 && intval($drSensor['assignment_id'])>0){
			$error=1;
			$message.=" (O υπάλληλος δεν έχει επιστρέψει εξοπλισμό) ";
		}
		
		if($error==0){
				$insertQuery = "INSERT INTO assignments(user_id, employee_id, sensor_id, assignment_date,date_insert) VALUES ('".$user_id."','".$employee_id."','".$sensor_id."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
				$result = $db->sql_query($insertQuery);
		}
		
		if($error==0){
			$data = array();
			array_push($data,array(
					'error' => '0',
					'message' => 'Η ανάθεση ολοκληρώθηκε'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			//echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'error' => '1',
				'message' => 'Προέκυψαν σφάλματα! '.$message
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else 	if(isset($_GET['func']) && $_GET['func']=='completeassignment') {
		//https://panel.wear4safe.eu/api/api.php?func=completeassignment&assignment=6&user=16
		$error=0;
		$assignment_id = intval($_GET['assignment']);
		$user_id = intval($_GET['user']);
		
		if(intval($user_id)==0 ) $error=1;
		if(intval($assignment_id)==0 ) $error=1;


		if($error==0){
				//τσεκ αν υπάρχει η ανάθεση
				$db = new sql_db($host, $dbuser, $dbpass, $database, false);
				$data = array();
				$dr = $db->RowSelectorQuery("SELECT * FROM assignments WHERE user_id=".$user_id." AND assignment_id=".$assignment_id);

				if(intval($dr['assignment_id'])>0){
					$error=0;
					$updateQuery = "UPDATE assignments SET return_date='".date('Y-m-d H:i:s')."' WHERE assignment_id=".$assignment_id;
					$result = $db->sql_query($updateQuery);
				} else {
					$error=1;
				}
		}
		
		if($error==0){
			$data = array();
			array_push($data,array(
					'error' => '0',
					'message' => 'Η ανάθεση ολοκληρώθηκε'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			//echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'error' => '1',
				'message' => 'Προέκυψαν σφάλματα'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else 	if(isset($_GET['func']) && $_GET['func']=='removeassignment') {
		//https://panel.wear4safe.eu/api/api.php?func=removeassignment&assignment=6&user=16
		$error=0;
		$assignment_id = intval($_GET['assignment']);
		$user_id = intval($_GET['user']);
		
		if(intval($user_id)==0 ) $error=1;
		if(intval($assignment_id)==0 ) $error=1;


		if($error==0){
				//τσεκ αν υπάρχει η ανάθεση
				$db = new sql_db($host, $dbuser, $dbpass, $database, false);
				$data = array();
				$dr = $db->RowSelectorQuery("SELECT * FROM assignments WHERE user_id=".$user_id." AND assignment_id=".$assignment_id);

				if(intval($dr['assignment_id'])>0){
					$error=0;
					$removeQuery = "DELETE FROM assignments WHERE assignment_id=".$assignment_id;
					$result = $db->sql_query($removeQuery);
				} else {
					$error=1;
				}
		}
		
		if($error==0){
			$data = array();
			array_push($data,array(
					'error' => '0',
					'message' => 'Η διαγραφή ολοκληρώθηκε'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			//echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'error' => '1',
				'message' => 'Προέκυψαν σφάλματα'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else 	if(isset($_GET['func']) && $_GET['func']=='conditions') {
		//https://alerts.smartiscity.gr/api/api.php?func=conditions&organization=2&type=2
		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$organization=intval($_GET['organization']);
		$type=intval($_GET['type']);
		
		$query="SELECT t1.*,t2.sensor_name,t2.sensortype_id,t3.sensortype_name FROM conditions t1 INNER JOIN sensors t2 ON t1.sensor_id=t2.sensor_id INNER JOIN sensortypes t3 ON t2.sensortype_id=t3.sensortype_id
		WHERE 1=1 AND t1.is_valid='True' AND t1.organization_id=".$organization." AND t2.sensortype_id=".$type;	
		$result = $db->sql_query($query);
		$counter=0;
		
		
		while ($dr = $db->sql_fetchrow($result)){
			$data[$counter]['condition_id']=$dr["condition_id"];
			$data[$counter]['condition_name']=$dr["condition_name"];
			$data[$counter]['sensor_name']=$dr["sensor_name"];
			$data[$counter]['sensortype_name']=$dr["sensortype_name"];
									
											
			if($dr['specifier1']=='1') $sp1=' < ';
			if($dr['specifier1']=='2') $sp1=' <= ';
			if($dr['specifier1']=='3') $sp1=' = ';
			if($dr['specifier1']=='4') $sp1=' >= ';
			if($dr['specifier1']=='5') $sp1=' > ';
		
			if($dr['operator']=='0') $op='';
			if($dr['operator']=='1') $op='AND';
			if($dr['operator']=='1') $op='OR';
			
			if($dr['specifier2']=='1') $sp2=' < ';
			if($dr['specifier2']=='2') $sp2=' <= ';
			if($dr['specifier2']=='3') $sp2=' = ';
			if($dr['specifier2']=='4') $sp2=' >= ';
			if($dr['specifier2']=='5') $sp2=' > ';
		
			$res="#value#".$sp1.$dr['value1'];
			if(intval($dr['operator'])>0){
				$res.=" ".$op." "."#value#".$sp2.$dr['value2'];
			}
			$condition = $res;
			$data[$counter]['condition']=$condition;
			/**/

			$counter++;
		}
		
		if(sizeof($data)>0){
			$json = json_encode($data);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'condition_id' => '0',
				//'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}	
	} else 	if(isset($_GET['func']) && $_GET['func']=='fleeto') {	
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = json_decode(file_get_contents('php://input'), true);
		//var_dump($data);
		//exit;
		//00000191
		$insertQuery="";
		for($i=0;$i<sizeof($data);$i++){
			$oldDtServer = $data[$i]['dt_server']; //Ωρα server στη βάση του fleeto. Την κρατάω σαν κριτήριο για τα queries
			$dtServer = date('Y-m-d H:i:s', strtotime($data[$i]['dt_server']. ' + 3 hours'));
			$dtTracker = date('Y-m-d H:i:s', strtotime($data[$i]['dt_tracker']. ' + 3 hours'));
			if(intval($data[$i]['val'])==0){
				$updateQuery = "UPDATE sensors SET lastupdate='".$oldDtServer."' WHERE sensor_id='".$data[$i]['sensor_id']."'";
				$message = $data[$i]['dt_server'];
				$results = $db->sql_query($updateQuery);
			} else {
				$updateQuery = "UPDATE sensors SET lastupdate='".$oldDtServer."' WHERE sensor_id='".$data[$i]['sensor_id']."'";
				$resultUpdate = $db->sql_query($updateQuery);
				$checkRow = $db->RowSelectorQuery("SELECT * FROM data WHERE sensor_id='".$data[$i]['sensor_id']."' AND dt_server='".$dtServer."'");
				if(intval($checkRow['alert_id'])==0){
					$insertQuery="INSERT INTO data(sensor_id, dt_server, dt_tracker, lat, lng, altitude, angle, speed, probe, val,sos) VALUES ('".$data[$i]['sensor_id']."','".$dtServer."','".$dtTracker."','".$data[$i]['lat']."','".$data[$i]['lng']."','".$data[$i]['altitude']."','".$data[$i]['angle']."','".$data[$i]['speed']."','".$data[$i]['probe']."','".$data[$i]['val']."','".$data[$i]['sosval']."')";
					$resultInsert = $db->sql_query($insertQuery);
					//Δες τα όρια του συγκεκριμένου probe
					$drSensor = $db->RowSelectorQuery("SELECT * FROM sensors WHERE sensor_id = '".$data[$i]['sensor_id']."'");
					$prof=0;
					//if($drSensor['sensor1_name']==$data[$i]['probe']) $prof = $drSensor['sensor1_map_id'];
					//if($drSensor['sensor2_name']==$data[$i]['probe']) $prof = $drSensor['sensor2_map_id'];
					//if($drSensor['sensor3_name']==$data[$i]['probe']) $prof = $drSensor['sensor3_map_id'];
					//if($drSensor['sensor4_name']==$data[$i]['probe']) $prof = $drSensor['sensor4_map_id'];
					//if($drSensor['sensor5_name']==$data[$i]['probe']) $prof = $drSensor['sensor5_map_id'];
					
					if(strcasecmp($drSensor['sensor1_name'],$data[$i]['probe']) == 0) $prof = $drSensor['sensor1_map_id'];
					if(strcasecmp($drSensor['sensor2_name'],$data[$i]['probe']) == 0) $prof = $drSensor['sensor2_map_id'];
					if(strcasecmp($drSensor['sensor3_name'],$data[$i]['probe']) == 0) $prof = $drSensor['sensor3_map_id'];
					if(strcasecmp($drSensor['sensor4_name'],$data[$i]['probe']) == 0) $prof = $drSensor['sensor4_map_id'];
					if(strcasecmp($drSensor['sensor5_name'],$data[$i]['probe']) == 0) $prof = $drSensor['sensor5_map_id'];
					
					//Αν δεν είναι μέσα στα αποδεκτά όρια γράψε την εγγραφή στα alerts
					$drLimits = $db->RowSelectorQuery("SELECT * FROM map WHERE map_id='".$prof."'");
					if(intval($data[$i]['val'])<$drLimits['min'] || intval($data[$i]['val'])>$drLimits['max'] || $data[$i]['sosval']=='1'){
						$alertQuery = "INSERT INTO alerts(sensor_id, min,max, dt_server, dt_tracker, lat, lng, altitude, angle, speed, probe, val,sos) VALUES ('".$data[$i]['sensor_id']."','".$drLimits['min']."','".$drLimits['max']."','".$dtServer."','".$dtTracker."','".$data[$i]['lat']."','".$data[$i]['lng']."','".$data[$i]['altitude']."','".$data[$i]['angle']."','".$data[$i]['speed']."','".$data[$i]['probe']."','".$data[$i]['val']."','".$data[$i]['sosval']."')";
						$resultAlert = $db->sql_query($alertQuery);
					}
					$message = $data[$i]['dt_server'];
				}

			}
		}
		//echo $data[$i]['dt_server'].'<br>';
		//file_put_contents('log.html', date('Y-m-d H:i:s')."<br>\n\r", FILE_APPEND);
		//$req_dump = print_r($data, true);	
		//$req_dump = file_get_contents("php://input");
		//file_put_contents('log.html', $req_dump."<br/>", FILE_APPEND);	
		echo '{"status":"'.$message.'"}';	
		/**/
	} else 	if(isset($_GET['func']) && $_GET['func']=='clearDB') { 
		//https://panel.wear4safe.eu/api/api.php?func=clearDB
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$result = $db->sql_query("DELETE FROM data");
		$result = $db->sql_query("DELETE FROM alerts");
		$result = $db->sql_query("UPDATE sensors SET lastupdate = '2023-10-10 06:00:00' WHERE sensor_id IN (31,27,28,26,32,29,30,25)");
		echo '{"result":"ok"}';
		exit;
	
	} else 	if(isset($_GET['func']) && $_GET['func']=='alerts') { 
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		//https://panel.wear4safe.eu/api/api.php?func=alerts&organization=1
		//https://panel.wear4safe.eu/api/api.php?func=alerts&sector=1
		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
		//$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$organization_id=intval($_GET['organization']);
		$sector_id=intval($_GET['sector']);
		$filter="";
		$filter.=(intval($organization_id)>0?" AND sensor_id IN (SELECT sensor_id FROM sensors WHERE organization_id='".$organization_id."')":"");
		//$filter.=(intval($sector_id)>0?" AND employee_id IN (SELECT employee_id FROM employees WHERE sector_id='".$sector_id."')":"");
		$filter.=(intval($sector_id)>0?" AND sensor_id IN (SELECT sensor_id FROM assignments WHERE isnull(return_date) AND employee_id IN (SELECT employee_id FROM employees WHERE sector_id='".$sector_id."'))":"");
		$query="SELECT * FROM alerts WHERE 1=1 ".$filter." ORDER BY alert_id DESC";

		$result = $db->sql_query($query);
		$counter=0;
		
		while ($dr = $db->sql_fetchrow($result)){
			//print_r($dr);
			$drSensor = $db->RowSelectorQuery("SELECT * FROM sensors WHERE sensor_id = '".$dr['sensor_id']."'");
			$map=0;
			if($drSensor['sensor1_name']==$dr['probe']) $map = $drSensor['sensor1_map_id'];
			if($drSensor['sensor2_name']==$dr['probe']) $map = $drSensor['sensor2_map_id'];
			if($drSensor['sensor3_name']==$dr['probe']) $map = $drSensor['sensor3_map_id'];
			if($drSensor['sensor4_name']==$dr['probe']) $map = $drSensor['sensor4_map_id'];
			if($drSensor['sensor5_name']==$dr['probe']) $map = $drSensor['sensor5_map_id'];
			$mapName = $db->RowSelectorQuery("SELECT * FROM map WHERE map_id='".$map."'");	
			//echo "SELECT * FROM employees WHERE employee_id = (SELECT employee_id FROM assignments WHERE sensor_id = '".$dr['sensor_id']."' ORDER BY date_insert DESC LIMIT 1)";
			$drEmployee = $db->RowSelectorQuery("SELECT * FROM employees WHERE employee_id = (SELECT employee_id FROM assignments WHERE sensor_id = '".$dr['sensor_id']."' ORDER BY date_insert DESC LIMIT 1)");	
			array_push($data,array(
				'alert_id' => $dr['alert_id'],
				'employee_id' => $drEmployee['employee_id'],
				'employee_name' => $drEmployee['firstname'].' '.$drEmployee['surname'],
				'sensor_id' => $dr['sensor_id'],
				'sensor_name' => $drSensor['sensor_name'],
				'map_name' => $mapName['map_name'],
				'dt_server' => $dr['dt_server'],
				'dt_tracker' => $dr['dt_tracker'],
				'lat' => $dr['lat'],
				'lng' => $dr['lng'],
				'altitude' => $dr['altitude'],
				'angle' => $dr['angle'],
				'speed' => $dr['speed'],
				'probe' => $dr['probe'],
				'val' => $dr['val'],
				'min' => $dr['min'],
				'max' => $dr['max'],
				'sos' => $dr['sos']
				)
			);	
			$counter++;
		}
		
		if(sizeof($data)>0){
			$json = json_encode($data);
			//$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'alert_id' => '0'
				)
			);
			$json = json_encode($data);
			//$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} 

	
?>


