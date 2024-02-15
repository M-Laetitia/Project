<?php

namespace App\Service;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)  
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250, ?int $maxFileSize = 5)
    {
        // Generate a new and unique name for the image
        // The md5 function is a hashing function that produces a 32-character hexadecimal checksum
        // uniqid(rand(), true) generates a unique string based on the current timestamp with microsecond resolution (the use of true adds more entropy to the string).
        // Md5 takes this unique string and converts it into a 32-character hexadecimal hash.
        $file = md5(uniqid(rand(), true)) . '.' . $picture->guessExtension();

        // Retrieve image information
        $picture_infos = getimagesize($picture); // returns an array with the dimensions and other details of the image.

        if ($picture_infos == false) {
            throw new Exception('Incorrect image format');
        }

        // Check the image format
        // MIME (Multipurpose Internet Mail Extensions) types are identifiers for file formats and file types on the Internet. They are a standard way of indicating the nature and format of a document, file, or assortment of bytes.
        switch ($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/jpg':
                $picture_source = imagecreatefromjpg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Incorrect image format');
        }

        // Check file size
        $maxFileSizeInMb = $maxFileSize * 1024 * 1024; // Convert MB to bytes
        if ($picture->getSize() > $maxFileSizeInMb) {
            throw new Exception('File size exceeds the maximum allowed limit.');
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
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }


        // on crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        // Calculate the new dimensions while preserving the aspect ratio
        // $aspectRatio = $imageWidth / $imageHeight;
        // $newWidth = min($imageWidth, $maxWidth);
        // $newHeight = $newWidth / $aspectRatio;

        // // Resize the image
        // $resized_picture2 = imagecreatetruecolor($newWidth, $newHeight);
        // imagecopyresampled($resized_picture2, $picture_source, 0, 0, $src_x, $src_y, $newWidth, $newHeight, $imageWidth, $imageHeight);


        $path = $this->params->get('artists_directory') .$folder;
        

        // on crée le dossier de destination s'il n'existe pas
        if(!file_exists($path. '/works/mini')) {
            mkdir($path. '/works/mini', 0755, true);
        }

        $destinationPath = $path . '/works/';

        // Create the destination directory if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }


        // on stocke l'image recadrée (miniature)
        imagewebp($resized_picture, $path . '/works/mini/' . $width. 'x' . $height . '-' . $file );
        // on stocke l'image recadrée 2 (max 800)
        // imagewebp($resized_picture2, $path . '/works/' . $newWidth . 'x' . $newHeight . '-' . $file);

        // dd($path);
        // "C:\laragon\www\PROJET\Project/public/images/artists/8"
        $picture->move($destinationPath,  $file);

        return $file;
    }



    public function delete($file, ?string $folder ="", ?int $width = 250, ?int $height = 250)
    {
        if($file !== 'default.webp') { // ne pas supprimer le fichier par défault
            $success = false;
            $path = $this->params->get('artists_directory') .$folder;
      
            $miniPictureName = $width. 'x' . $height . '-' . $file ;

            $WorkMiniDirectory = 'images/artists/' . $folder . '/works/mini';

            $picturePath = $this->params->get('kernel.project_dir') . '/public/' . $WorkMiniDirectory . '/' . $miniPictureName;

            if(file_exists($picturePath)) {
                unlink($picturePath);
                $success = true;
            }

            $xorkDirectory = 'images/artists/' . $folder . '/works';
            $originalPicutrePath = $this->params->get('kernel.project_dir') . '/public/' . $xorkDirectory . '/' . $file;
           
            if(file_exists($originalPicutrePath)) {
                unlink($originalPicutrePath);
                $success = true;
            }

            return $success; 
        }

        return false;
        
    }

}