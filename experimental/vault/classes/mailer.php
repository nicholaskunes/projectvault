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
                            $mail->setFrom('root@vps160551.vps.ovh.ca', 'Mailer');
                            $mail->addAddress('kunes.nick0@gmail.com', 'Joe User');                     
                            $mail->isHTML(true);
                            $mail->Subject = 'test';
                            $mail->Body    = 'test';
                            $mail->AltBody = 'test';
                            
                            $mail->send();
                        }
                        catch (Exception $e) {
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        }
