<?php

namespace App\Service;

use PHPUnit\Util\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureService{
    private $params;
    public function __construct(ParameterBagInterface $params){
        $this->params = $params;
    }

    public function add(UploadedFile $picture, SluggerInterface $slugger, $directory)
    {
        $originalFilename = $picture;
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();
        try {
            $picture->move(
                $this->params->get($directory),
                $newFilename
            );
        } catch (FileException $e) {

        }
        return $newFilename;
    }

}