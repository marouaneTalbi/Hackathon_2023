<?php

namespace App\Service;

use PHPUnit\Util\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
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

    public function set(UploadedFile $picture, SluggerInterface $slugger, $directory, $oldFilename = null)
    {
        $filesystem = new Filesystem();

        // Supprime l'ancien fichier s'il existe
        if ($oldFilename) {
            $oldFilePath = $this->params->get($directory) . '/' . $oldFilename;
            if ($filesystem->exists($oldFilePath)) {
                $filesystem->remove($oldFilePath);
            }
        }

        $originalFilename = $picture->getClientOriginalName();
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();
        try {
            $picture->move(
                $this->params->get($directory),
                $newFilename
            );
        } catch (FileException $e) {
            // Gérez les erreurs de fichier ici
        }

        return $newFilename;
    }





}