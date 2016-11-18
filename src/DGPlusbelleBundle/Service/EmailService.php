<?php

namespace DGPlusbelleBundle\Service;

use Symfony\Component\Plantillas\EngineInterface;
class EmailService 
{
    protected $info; 
    protected $mail;
    protected $templating;
    protected $view;
    protected $subject;
    protected $from;
    protected $reply;
    protected $to;
    protected $body;
    
    public function __construct($mail, $templating ) {
	$this->templating = $templating;
      
        $this->mail   = $mail;
        $this->subject = 'La Plus Belle & Sonodigest';
        $this->from   = 'gerencia@laplusbelle.com.sv'; 
        $this->info = 'La Plus Belle y Sonodigest';
    }  
    
    public function setEmail($to,$bcc=null){
        
        $this->view   = 'DGPlusbelleBundle:Emails:test.html.twig';
        $this->to     = $to;
        $contenido    = 'Este correo es enviado desde el sistema de La Plus Belle y Sonodigest';
        $this->body = $this->templating->render($this->view, array('body'=>$contenido));
        $this->sendEmail($this->to,null,$bcc,null,$this->body);
        
    }
    
    public function sendEmail($to, $cc, $bcc,$replay, $body,$nombreArchivos,$asunto){
        $email = \Swift_Message::newInstance();
        $email->setContentType('text/html');                    
        $email->setFrom($this->from,'La Plus Belle y Sonodigest');
        $email->setTo($to);
        
        foreach($nombreArchivos as $key=>$nombre){
            $email->attach(\Swift_Attachment::fromPath('http://190.53.176.116/laplusbelle/Photos/correos/'.$nombre));
        }
        
        if($cc != null ){
            $email->setCc($cc);
        }
        if($replay != null ){
            $email->setReplyTo($replay);
        }else{
            $email->setReplyTo('no-reply@laplusbelle.com.sv');            
        }
        if($bcc != null ){
            $email->setBcc($bcc);
        }
        //$email->setSubject($this->subject);  
        $email->setSubject($asunto);  
        $email->setBody($body); 
        $this->mail->send($email);
    }
    
    public function sendEmailReminder($subject,$to, $cc, $bcc,$replay, $body){
        $email = \Swift_Message::newInstance();
        $email->setContentType('text/html');                    
        $email->setFrom($this->from);
        $email->setTo($to);
        if($cc != null ){
        $email->setCc($cc);
        }
        if($replay != null ){
        $email->setReplyTo($replay);
        }else{
        $email->setReplyTo('system@digitalitygarage.com');            
        }
        if($bcc != null ){
        $email->setBcc($bcc);
        }
        $email->setSubject($subject);  
        $email->setBody($body); 
        $this->mail->send($email);
    }

}