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


                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host       = 'localhost'; 
							$mail->SMTPAutoTLS = false;
                            $mail->Port       = 25; 							                            
                            $mail->setFrom('no-reply@surrealarcher.com', 'no-reply@surrealarcher.com');
                            $mail->addAddress('kunes.nick0@gmail.com', 'User');                     
                            $mail->isHTML(true);
                            $mail->Subject = 'App Name: Confirm your email to access your vault';
                            $mail->Body    = 'placeholder confirmation text';
                            
                            $mail->send();
                        }
                        catch (Exception $e) {
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        }
