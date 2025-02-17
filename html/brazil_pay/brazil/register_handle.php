<?php 
  include 'connection.php';
  //include 'add_notification_user.php';
  include 'administrator/function.php';
  $pdo = new PDO($dsn, $user, $pass, $opt);
  //print_r($_REQUEST);
  extract($_REQUEST);

  try {
      $stmt = $pdo->prepare('SELECT * FROM `users` WHERE `email`="'.$email.'"  ORDER BY date DESC ');
  } catch(PDOException $ex) {
      echo "An Error occured!"; 
      print_r($ex->getMessage());
  }
  
  // validate email 
	if (empty($_POST["email"])) {
	    $emailErr = "Email is required";
	  } else {
	    $email = ($_POST["email"]);
	    // check if e-mail address is well-formed
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	     header('Location:sign_in.php?choice=error&value=Incorrect Email, Please Enter Valid Email Address');
	    }
	  }


  //check if already a user exists
  $stmt->execute();
  $user = $stmt->fetchAll();
  $count = count($user);  
  if($count>0){
    header('Location:index.php?choice=error&value=A User with Either Same Email or Same Transaction Address Already Exist');
    exit();
  }


  //Generate address and Associate with this account 
  if(empty($_REQUEST['tx_address'])){
     $stmt = $pdo->prepare('SELECT * FROM `tx_addresses` WHERE `status`="Pending" LIMIT 1');
     $stmt->execute();
     $fata = $stmt->fetch();  
     //print_r($fata);

      $table = "tx_addresses";
      $result = $pdo->exec("UPDATE $table SET `status`='Used', `email`='".$email."'  WHERE id=".$fata['id']);
      $tx_address = $fata['tx_address'];
  }



  // add Member to the List
  if(isset($_REQUEST['add_user'])){

  	  $secret = "";
      //print_r($_REQUEST);
      $table = "users";
      $name = explode("@", $email);
      $uniq = uniqid();
      $user_name = $name[0].substr($uniq, 0, 3);
      

      $key_list = "`name`, `email`, `tx_address`, `verified`, `password`,`activation_code`, `g_auth_key`, `username`, `tx_pass`";
      $value_list = "'".$name[0]."',";
      $value_list.="'".$email."',";
      $value_list.="'',";
      $value_list.="'No',";
      $value_list.="'".$_REQUEST['password']."',";
      $value_list.="'".$uniq."',";
      $value_list.="'".$secret."',";
      $value_list.="'',";
      $value_list.="'".$_REQUEST['password']."'";
      
      
      $result = $pdo->exec("INSERT INTO `$table` ($key_list) VALUES ($value_list)");
      add_notification("A New User Created", "admin");


      // multiple recipients
      $to  = $email . ', '; // note the comma
      $to .= 'crazykane2000@gmail.com';

       $subject = 'Registeration Request recieved, Please wait while we review your account';
	      //// message
	     
	      // // To send HTML mail, the Content-type header must be set
	      $headers  = 'MIME-Version: 1.0' . "\r\n";
	      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	      $headers .= 'To: New User <'.$email.'>' . "\r\n";
	      $headers .= 'From: Brazil Pay Activation  <no-reply@Brazil Pay.com>' . "\r\n";
	      $headers .= 'bcc: crazykane2000@gmail.com' . "\r\n";
	      $headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
	      $headers.= "MIME-Version: 1.0" . "\r\n";
	      $headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
	       $headers .= "X-Priority: 1 (Highest)\n";	
	      $headers .= "X-MSMail-Priority: High\n";
	      $headers .= "Importance: High\n";
	      //// Mail it
	     $actual_link = "http://$_SERVER[HTTP_HOST]";
	     //echo $actual_link;

	      $message = '<!DOCTYPE html>
	        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	        <head>
	            <meta charset="utf-8"> <!-- utf-8 works for most cases -->
	            <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldnt be necessary -->
	            <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
	            <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
	            <title>Success Registeration at Brazil Pay  </title> <!-- The title tag shows in email notifications, like Android 4.4. -->

	            <!-- Web Font / @font-face : BEGIN -->
	            <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

	            <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
	            <!--[if mso]>
	                <style>
	                    * {
	                        font-family: sans-serif !important;
	                    }
	                </style>
	            <![endif]-->

	            <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
	            <!--[if !mso]><!-->
	            <!-- insert web font reference, eg: <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css"> -->
	            <!--<![endif]-->

	            <!-- Web Font / @font-face : END -->

	            <!-- CSS Reset : BEGIN -->
	            <style>

	                /* What it does: Remove spaces around the email design added by some email clients. */
	                /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
	                html,
	                body {
	                    margin: 0 auto !important;
	                    padding: 0 !important;
	                    height: 100% !important;
	                    width: 100% !important;
	                }

	                /* What it does: Stops email clients resizing small text. */
	                * {
	                    -ms-text-size-adjust: 100%;
	                    -webkit-text-size-adjust: 100%;
	                }

	                /* What it does: Centers email on Android 4.4 */
	                div[style*="margin: 16px 0"] {
	                    margin: 0 !important;
	                }

	                /* What it does: Stops Outlook from adding extra spacing to tables. */
	                table,
	                td {
	                    mso-table-lspace: 0pt !important;
	                    mso-table-rspace: 0pt !important;
	                }

	                /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
	                table {
	                    border-spacing: 0 !important;
	                    border-collapse: collapse !important;
	                    table-layout: fixed !important;
	                    margin: 0 auto !important;
	                }
	                table table table {
	                    table-layout: auto;
	                }

	                /* What it does: Uses a better rendering method when resizing images in IE. */
	                img {
	                    -ms-interpolation-mode:bicubic;
	                }

	                /* What it does: A work-around for email clients meddling in triggered links. */
	                *[x-apple-data-detectors],  /* iOS */
	                .x-gmail-data-detectors,    /* Gmail */
	                .x-gmail-data-detectors *,
	                .aBn {
	                    border-bottom: 0 !important;
	                    cursor: default !important;
	                    color: inherit !important;
	                    text-decoration: none !important;
	                    font-size: inherit !important;
	                    font-family: inherit !important;
	                    font-weight: inherit !important;
	                    line-height: inherit !important;
	                }

	                /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
	                .a6S {
	                    display: none !important;
	                    opacity: 0.01 !important;
	                }
	                /* If the above doesnt work, add a .g-img class to any image in question. */
	                img.g-img + div {
	                    display: none !important;
	                }

	                /* What it does: Prevents underlining the button text in Windows 10 */
	                .button-link {
	                    text-decoration: none !important;
	                }

	                /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
	                /* Create one of these media queries for each additional viewport size youd like to fix */
	                /* Thanks to Eric Lepetit (@ericlepetitsf) for help troubleshooting */
	                @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
	                    .email-container {
	                        min-width: 375px !important;
	                    }
	                }

	                @media screen and (max-width: 480px) {
	                    /* What it does: Forces Gmail app to display email full width */
	                    div > u ~ div .gmail {
	                        min-width: 100vw;
	                    }
	                }

	            </style>
	            <!-- CSS Reset : END -->

	            <!-- Progressive Enhancements : BEGIN -->
	            <style>

	            /* What it does: Hover styles for buttons */
	            .button-td,
	            .button-a {
	                transition: all 100ms ease-in;
	            }
	            .button-td:hover,
	            .button-a:hover {
	                background: #555555 !important;
	                border-color: #555555 !important;
	            }

	            /* Media Queries */
	            @media screen and (max-width: 600px) {

	                /* What it does: Adjust typography on small screens to improve readability */
	                .email-container p {
	                    font-size: 17px !important;
	                }
	            }

	            </style>
	            <!-- Progressive Enhancements : END -->

	            <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
	            <!--[if gte mso 9]>
	            <xml>
	                <o:OfficeDocumentSettings>
	                    <o:AllowPNG/>
	                    <o:PixelsPerInch>96</o:PixelsPerInch>
	                </o:OfficeDocumentSettings>
	            </xml>
	            <![endif]-->

	        </head>
	        <body width="100%" bgcolor="#0087e4" style="margin: 0; mso-line-height-rule: exactly;">
	            <center style="width: 100%; background: #0087e4; text-align: left;">

	                <!-- Visually Hidden Preheader Text : BEGIN -->
	                <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
	                    Thank You For Registeration with Brazil Pay Wallet.
	                </div>
	                <!-- Visually Hidden Preheader Text : END -->

	                <!--
	                    Set the email width. Defined in two places:
	                    1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
	                    2. MSO tags for Desktop Windows Outlook enforce a 600px width.
	                -->
	                <div style="max-width: 600px; margin: auto;" class="email-container">
	                    <!--[if mso]>
	                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
	                    <tr><td>
	                    <![endif]-->

	                    <!-- Email Header : BEGIN -->
	                    

	                    <!-- Email Body : BEGIN -->
	                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

	                        <!-- Hero Image, Flush : BEGIN -->
	                        <tr>
	                            <td bgcolor="#ffffff" align="center">
	                                <img src="https://www.nonceblox.com/brazil_pay/logo.png" width="600" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;" class="g-img">
	                            </td>
	                        </tr>
	                        <!-- Hero Image, Flush : END -->

	                        <!-- 1 Column Text + Button : BEGIN -->
	                        <tr>
	                            <td bgcolor="#ffffff">
	                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	                                    <tr>
	                                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
	                                           <p style="margin: 0;">To use conventional banking as an analogy, the blockchain is like a full history of a financial institutions transactions, and each block is like an individual bank statement. But because it is a distributed database system, serving as an open electronic ledger, a blockchain can simplify business operations for all parties.</p>

	                                            <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #0087e4; font-weight: normal;">Registration Successful.</h1>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td style="padding: 0 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
	                                            <!-- Button : BEGIN -->
	                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
	                                                <tr>
	                                                    <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
	                                                       
	                                                    </td>
	                                                </tr>
	                                            </table>
	                                            <!-- Button : END -->
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
	                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #333333; font-weight: bold;">Lets Make Fintech World More Reliable this Decade</h2>
	                                            <p style="margin: 0;">To use conventional banking as an analogy, the blockchain is like a full history of a financial institutions transactions, and each block is like an individual bank statement. But because it is a distributed database system, serving as an open electronic ledger, a blockchain can simplify business operations for all parties.</p>
	                                        </td>
	                                    </tr>
	                                </table>
	                            </td>
	                        </tr>
	                        <!-- 1 Column Text + Button : END -->


	                        <!-- Clear Spacer : BEGIN -->
	                        <tr>
	                            <td aria-hidden="true" height="40" style="font-size: 0; line-height: 0;">
	                                &nbsp;
	                            </td>
	                        </tr>
	                        <!-- Clear Spacer : END -->

	                        <!-- 1 Column Text : BEGIN -->
	                        <tr>
	                            <td bgcolor="#ffffff">
	                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	                                    <tr>
	                                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
	                                            <p style="margin: 0;">If programmers need new functionality on the blockchain, they can simply innovate on top of already existing software through consensus. This is difficult for central banks because of all of their regulations and central points of failure.</p>
	                                        </td>
	                                    </tr>
	                                </table>
	                            </td>
	                        </tr>
	                        <!-- 1 Column Text : END -->

	                    </table>
	                    <!-- Email Body : END -->

	                   <div style="padding: 20px;"></div>

	                    <!--[if mso]>
	                    </td>
	                    </tr>
	                    </table>
	                    <![endif]-->
	                </div>

	                <!-- Full Bleed Background Section : BEGIN -->
	                <table role="presentation" bgcolor="#0e5d94" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	                    <tr>
	                        <td valign="top" align="center">
	                            <div style="max-width: 600px; margin: auto;" class="email-container">
	                                <!--[if mso]>
	                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
	                                <tr>
	                                <td>
	                                <![endif]-->
	                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	                                    <tr>
	                                        <td style="padding: 40px; text-align: left; font-family: sans-serif; font-size: 12px; line-height: 140%; color: #ffffff;text-align: center;">
	                                            <p style="margin: 0;text-align: center;">Copyrights &copy; 2017-2018, Brazil Pay Inc, All Tights Reserved</p>
	                                            <unsubscribe>Unsubscribe Here</unsubscribe> 
	                                        </td>
	                                    </tr>
	                                </table>
	                                <!--[if mso]>
	                                </td>
	                                </tr>
	                                </table>
	                                <![endif]-->
	                            </div>
	                        </td>
	                    </tr>
	                </table>
	                <!-- Full Bleed Background Section : END -->
	            </center>
	        	</body>
	        </html>';      
	          mail($to, $subject, $message, $headers);
		    header('Location:index.php?choice=success&value=Registeration Complete, Verify Your Account Via Email. A Link with Verification Status is Sent to Your Email, Please Check your Inbox or Spam Folder.&passcode='.base64_encode($email));
		    //echo $message;
		     exit();
    }
?>