<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureService
{
    private $params;


    public function __construct(ParameterBagInterface $params)  
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // On donne un nouveau nom à l'image
        $file = md5(uniqid(rand(), true)) . '.' . $picture->guessExtension();

        // On crée le dossier de destination s'il n'existe pas
        $path = $this->params->get('artists_directory') . $folder;
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // On stocke l'image
        $picture->move($path, $file);

        return $file;
    }

    public function delete(string $file, ?string $folder ="", ?int $width = 250, ?int $height = 250)
    {
        if($file !== 'default.webp') { // ne pas supprimer le fichier par défault
            $success = false;
            $path = $this->params->get('artists_directory') .$folder;

            $mini = $path . '/mini/' . $width. 'x' . $height . '-' . $file ;;
            if(file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' .$fichier; // chemin original
            if(file_exists($orginal)) {
                unlink($mini);
                $success = true;
            }

            return $success; 
        }

        return false;
        
    }

}