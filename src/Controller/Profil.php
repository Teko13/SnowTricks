<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserFile;
use App\Form\FileFormType;
use App\StringTypeChecker\StringTypeChecker;
use App\UploadFile\UploadFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Profil extends AbstractController
{
    private UploadFile $fileUploader;
    private EntityManagerInterface $em;
    private StringTypeChecker $stringTypeChecker;
    public function __construct(UploadFile $fileUploader, StringTypeChecker $stringTypeChecker, EntityManagerInterface $em) {
        $this->em = $em;
        $this->fileUploader = $fileUploader;
        $this->stringTypeChecker = $stringTypeChecker;
    }
    private function changeUserImage(Request $request): void
    {
        $user = $this->getUser();
        $mediaRef = $request->request->all()["image_ref"];
        $mediaFile = $request->files->all()["image_file"];
        if(isset($mediaRef)) {
            if($this->stringTypeChecker->isUrl($mediaRef)) {
                $oldUserImage = $user->getAvatar();
                if($oldUserImage) {
                    $this->em->remove($oldUserImage);
                }
                $userImage = new UserFile;
                $userImage->setPath($mediaRef);
                $user->setAvatar($userImage);
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash("alert-success", "votre photo de profil a bien été mise a jour");
            }
        }
        if(isset($mediaFile)) {
            if($mediaFile) {
                $oldUserImage = $user->getAvatar();
                if($oldUserImage) {
                    $this->em->remove($oldUserImage);
                }
                $userImage = $this->fileUploader->userImageFile($mediaFile);
                if(!($userImage instanceof UserFile)) {
                    $this->addFlash('alert-warning', "Fichier non autorisé");
                }
                else {
                    $user->setAvatar($userImage);
                    $this->em->persist($user);
                    $this->em->flush();
                    $this->addFlash("alert-success", "votre photo de profil a bien été mise a jour");
                }
            }
        }
    }
    #[Route("/profil", name:"show-profile")]
    public function show(Request $request): Response
    {
        if(!$this->getUser()) {
            return $this->redirect("/login");
        }
        $userImgeForm = $this->createForm(FileFormType::class);
        $userImgeForm->handleRequest($request);
        if($userImgeForm->isSubmitted()) {
            $this->changeUserImage($request);
        }
        return $this->render("profil.html.twig", [
            'mediaForm' => $userImgeForm->createView()
        ]);
    }
}
