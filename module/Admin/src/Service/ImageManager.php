<?php
namespace Admin\Service;

use Application\Entity\Product;
use Application\Entity\Image;

class ImageManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveImages($temp){
        $_FILES = $temp;
        $httpadapter = new \Zend\File\Transfer\Adapter\Http();
        $httpadapter->setDestination('public/img/products/');
                
        $files = $httpadapter->getFileInfo();

        foreach ($files as $file => $info) {
            $httpadapter->receive($file);
        } 
        
        return $httpadapter->getFileName();           
    }

    public function deleteFile($filename){
        if (file_exists($filename)) {
            if (!unlink($filename)) {
                $Message = "Error deleting $filename";
                } else {
                    $Message = "Deleted";
                    }
        } else {
            $Message = "The file $filename does not exist";
        }
        return $Message;
    }  

    public function deleteFiles($images, $data) {
        $status = 1;
        for ($i = 0; $i < count($images); $i++) {
            if (!empty($data['image_'.$i.'_'])) {
                $fileImage[$i] = 'public'.$images[$i]['image'];
                $Message = $this->deleteFile($fileImage[$i]);
                if($Message != 'Deleted') $status = 0;
            }

            for ($j = 1; $j <= count($images[$i]['detail']); $j++) {
                if (!empty($data['imageDetail'.$j.'_'.$i.'_'])) {
                    $fileImage[$i] = 'public'.$images[$i]['detail'][$j-1];
                    $Message = $this->deleteFile($fileImage[$i]);
                    if($Message != 'Deleted') $status = 0;
                }
            } 
        }

        return $status;
    }  

}
