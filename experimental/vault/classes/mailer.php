<?php
/**
 * Class registration
 * handles the user registration
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


                        $mail             = new PHPMailer(true); // Passing `true` enables exceptions
                        try {
                            //Server settings
                            $mail->SMTPDebug = 2; // Enable verbose debug output
                            $mail->isSMTP(); // Set mailer to use SMTP
                            $mail->Host       = 'localhost'; // Specify main and backup SMTP servers
							//$mail->SMTPAuth = true;

                            //$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                            $mail->Port       = 25; // TCP port to connect to
							                            
                            //Recipients
                            $mail->setFrom('root@vps160551.vps.ovh.ca', 'Mailer');
                            $mail->addAddress('kunes.nick0@gmail.com', 'Joe User'); // Add a recipient
                            
                            //Content
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = 'Here is the subject';
                            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                            
                            $mail->send();
                            echo 'Message has been sent';
                        }
                        catch (Exception $e) {
                            echo 'Message could not be sent.';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        }
