<?php
namespace App\UploadFile;

use App\Entity\Trick;
use App\Entity\TrickFile;
use App\StringTypeChecker\StringTypeChecker;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class UploadFile {
    private StringTypeChecker $stringTypeChecker;
    private RequestStack $requestStack;
    private array $featuredImageExtension = ["jpeg", "jpg", "png"];
    private array $trickFileMediaExtension = ["jpeg", "jpg", "png", "mp4"];
    public function __construct(StringTypeChecker $tringTypeChecker, RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->stringTypeChecker = $tringTypeChecker;
    }
    private function uploadFile(UploadedFile $file): TrickFile
    {
        $fileExtension = $file->guessExtension();
        $fileName = md5(uniqid()). '.' . $fileExtension;
        $fileDir = 'uploads/';
        $file->move($fileDir, $fileName);
        $host = $this->requestStack->getCurrentRequest()->getHost();
        $port = $this->requestStack->getCurrentRequest()->getPort();
        $filePath = "http://".$host.":".$port."/uploads/".$fileName;
        if($fileExtension === "mp4") {
            $filePath = "<video controls><source src=$filePath></video>";
        }
        $trickFile = new TrickFile;
        $trickFile->setPath($filePath);
        return $trickFile;
    }
    public function addUploadedFeaturedImageFile(UploadedFile $file, Trick $trick): bool
    {
            if(!in_array($file->guessExtension(), $this->featuredImageExtension)) {
                return false;
            }
            // uploadFile method return an instance of TrickFile that already contains file path
            $featuredImageFile = $this->uploadFile($file);
            $featuredImageFile->setTypeFile("image")
            ->setFeaturedImage(true);
            if($trick->getFeaturedImage()) {
                $trick->removeFile($trick->getFeaturedImage());
            }
            $trick->addFile($featuredImageFile);
            return true;
    }
    public function addUploadedTrickFile(UploadedFile $file, Trick $trick): bool
    {
        $fileExtension = $file->guessExtension();
        if(!in_array($fileExtension, $this->trickFileMediaExtension)) {
            return false;
        }
        // uploadFile method return an instance of TrickFile that already contains file path
        $trickFile = $this->uploadFile($file);
        if ($fileExtension === "mp4") {
            $trickFile->setTypeFile("video");
        }
        else {
            $trickFile->setTypeFile("image");
        }
        $trickFile->setFeaturedImage(false);
        $trick->addFile($trickFile);
        return true;
    }
    private function uploadTrickFileRef(string $ref): ?TrickFile
    {
        $file = new TrickFile;
        $file->setPath($ref);
        if($this->stringTypeChecker->isEmbed($ref)) {
            $file->setTypeFile("video");
            
        }
        else if($this->stringTypeChecker->isUrl($ref)) {
            $file->setTypeFile("image");
        }
        else {
            return  null;
        }
        return $file;
    }
    public function addFeaturedImageRef($trick, $ref): bool
    {
        if($trick->getFeaturedImage()) {
            $trick->removeFile($trick->getFeaturedImage());
        }
        $file = $this->uploadTrickFileRef($ref);
        $file->setFeaturedImage(true);
        $trick->addFile($file);
        return true;
    }
    public function addTrickFileRef($trick, $ref): bool
    {
        $file = $this->uploadTrickFileRef($ref);
        if($file === null) {
            return false;
        }
        $file->setFeaturedImage(false);
        $trick->addFile($file);
        return true;
    }
}
