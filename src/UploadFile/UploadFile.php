<?php
namespace App\UploadFile;

use App\Entity\Trick;
use App\Entity\TrickFile;
use App\Entity\User;
use App\Entity\UserFile;
use App\StringTypeChecker\StringTypeChecker;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UploadFile {
    private StringTypeChecker $stringTypeChecker;
    private RequestStack $requestStack;
    private array $imageExtension = ["jpeg", "jpg", "png"];
    private array $trickFileMediaExtension = ["jpeg", "jpg", "png", "mp4"];
    public function __construct(StringTypeChecker $tringTypeChecker, RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->stringTypeChecker = $tringTypeChecker;
    }
    private function uploadFile(UploadedFile $file): string
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
        return $filePath;
    }
    public function addUploadedFeaturedImageFile(UploadedFile $file, Trick $trick): bool
    {
            if(!in_array($file->guessExtension(), $this->imageExtension)) {
                return false;
            }
            // uploadFile method return path of uploaded file
            $featuredImageFile = new TrickFile;
            $featuredImageFile->setPath($this->uploadFile($file))
            ->setTypeFile("image")
            ->setFeaturedImage(true);
            if($trick->getFeaturedImage()) {
                $trick->removeFile($trick->getFeaturedImage());
            }
            $trick->addFile($featuredImageFile);
            return true;
    }
    public function userImageFile(UploadedFile $file): UserFile|bool
    {
            if(!in_array($file->guessExtension(), $this->imageExtension)) {
                return false;
            }
            // uploadFile method return path of uploaded file
            $userImageFile = new UserFile;
            $userImageFile->setPath($this->uploadFile($file));
            return $userImageFile;
    }
    public function addUploadedTrickFile(UploadedFile $file, Trick $trick): bool
    {
        $fileExtension = $file->guessExtension();
        if(!in_array($fileExtension, $this->trickFileMediaExtension)) {
            return false;
        }
        // uploadFile method return path of uploaded file
        $trickFile = new TrickFile;
        $trickFile->setPath($this->uploadFile($file));
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
    public function addFeaturedImageRef(Trick $trick, $ref): bool
    {
        if($trick->getFeaturedImage()) {
            $trick->removeFile($trick->getFeaturedImage());
        }
        $file = $this->uploadTrickFileRef($ref);
        $file->setFeaturedImage(true);
        $trick->addFile($file);
        return true;
    }
    public function addTrickFileRef(Trick $trick, $ref): bool
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
