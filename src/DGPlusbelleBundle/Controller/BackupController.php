<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Backup controller.
 *
 * @Route("/admin/backup")
 */
class BackupController extends Controller{
    
    /**
     * @Route("/", name="admin_backup")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Backup:backup.html.twig")
     */
    public function indexAction(Request $request)
    {
        $estado= $request->get('estado');
        //var_dump($estado);
//        if(!isset($estado)){
//            $estado = 1;
////            echo "if";
//        }
//        else{
//            $estado = 0;
//        }
        
        return array(
            'estado' => $estado,
        );
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