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

    public function add(UploadedFile $picture, ?string $folder ='', ?int $width = 250, ?int $height = 250) 
    {
        // on donne un nouveau nom à l'image
        $file = md5(uniqid(rand(), true)) . '.jpeg';

        // on récupère les infos de l'image
        $picture_infos = getimagesize($picture);

        if ($picture_infos == false) {
            throw new Exception('Format d\'image incorrect');
        }

        // on vérifie le format de l'image
        switch($picture_infos['mime']) {
            case 'image/png' :
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg' :
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp' :
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrect');
        }

        // on recadre l'image
        // on récupère les dimensions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // on vérifie l'orientation de l'image 
        // triple comparaison (inférieure, égale ou supérieure)
        // résultat : -1, 0, 1
        switch($imageWidth <=> $imageHeight) {
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;

            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;

            case 1: // paysage
                $squareSize = $imageWidth;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        // on crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('artists_directory') .$folder;

        // on crée le dossier de destination s'il n'existe pas
        if(!file_exists($path. '/mini')) {
            mkdir($path. '/mini', 0755, true);
        }

        // on stocke l'image recadrée
        imagewebp($resized_picture, $path . '/mini/' . $width. 'x' . $height . '-' . $file );

        $picture->move($path . '/' . $file);

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