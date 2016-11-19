<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
/**
 * @Route("/secured")
 */
class SecuredController extends Controller
{

    /**
     * @Route("/login", name="plusbelle_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }
        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        //ladybug_dump($error);
        return array(
            'last_username' => $lastUsername,
            'error' => $error,
            
        );
        /*ladybug_dump($error);
        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'errors'         => $error,
        );*/
    }

    /**
     * @Route("/login_check", name="plusbelle_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
        
    }

    /**
     * @Route("/logout", name="plusbelle_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
    
    
    
    
    
    
    /**
     * @Route("/backup", name="create_backup")
     * @Method("GET")
     * @Template()
     */
    public function createAction()
    {
    //echo PHP_OS; 
    //include_once "conexion/def.php";
    set_time_limit ( 6000 );
    date_default_timezone_set("America/El_Salvador");
    $backupFile = "laplusbelle_".date("d-m-Y_H-i-s").".sql";
    $path = $this->container->getParameter('plusbelle.backup');
    
    $kernel = $this->get('kernel');
    //$path = $kernel->locateResource('@DGPlusbelleBundle/backup/'.$backupFile);
    $path = $this->get('kernel')->locateResource("@DGPlusbelleBundle/Resources/backup/");
    //var_dump($path);
    //$pathdirname($this->container->getParameter('kernel.root_dir')) . '/web/bundles/mybundle/myfiles' 
    
//    if(strpos(strtolower($path), 'win') !== false){
//        $path=str_replace("/","\\",$path);
//    }
        
        
    //var_dump($path);
    try {
        /////Hay que dar permisos al usuario de la base de datos, LOCK TABLES y SHOW DATABASES
        exec("mysqldump -h localhost -u admin -p 919293marvin marvinvi_demo_plusbella -R> ".$path.$backupFile);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    //die();
    // open some file for reading
    $file = 'backup/'.$backupFile;
    
    $file1 = $path.$backupFile;
    $fp = fopen($file1, 'r');
    
    // set up basic connection
    $conn_id = ftp_connect("digitalitygarage.com");
    $ftp_user_name="laplusbelle@digitalitygarage.com";
    $ftp_user_pass="919293marvin";

    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    // try to upload $file
     if (ftp_fput($conn_id, $file, $fp, FTP_ASCII)) {
         echo "Successfully uploaded $file\n";
     } else {
         echo "There was a problem while uploading $file\n";
     }

    // close the connection and the file handler
    ftp_close($conn_id);
    fclose($fp);
    die();
//    return $this->redirect($this->generateUrl('admin_backup'));
    return $this->redirect($this->generateUrl('admin_backup', array('estado' => '0')));

    }
    
    
}