<?php
namespace App\Controller;
use App\Entity\Trick;
use App\Form\FileFormType;
use App\Form\NewGroupeFormType;
use App\Form\TrickCreationFormType;
use App\ManageTrick\ManageTrick;
use App\UploadFile\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TrickController extends AbstractController {
    private ManageTrick $manageTrick;
    private AddNewGroupe $addNewGroupe;
    private UploadFile $fileUploader;
    public function __construct(AddNewGroupe $addNewGroupe, ManageTrick $manageTrick, UploadFile $fileUploader) 
    {
        $this->fileUploader = $fileUploader;
        $this->addNewGroupe = $addNewGroupe;
        $this->manageTrick = $manageTrick;
    }

    #[Route('/trick/{slug}',name: "trick_show")]
    function show(Trick $trick): Response
    {
        // dd($trick->getComments()->toArray());
        return $this->render("details.html.twig", compact("trick"));
    }
    #[Route('/create/trick/clear', name: "trick_clear")]
    public function trickClear(Request $request): Response 
    {
        if($request->getSession()->get("trick")) {
            $request->getSession()->remove("trick");
        }
        return $this->redirect("/create/trick");
    }
    #[Route('/edit/trick/clear', name: "edit_trick_clear")]
    public function editTrickClear(Request $request): Response 
    {
        if($request->getSession()->get("trick_edit")) {
            $request->getSession()->remove("trick_edit");
        }
        return $this->redirect("/create/trick");
    }
    private function removeTrickFileByName(Trick $trick, string $fileName): bool
    {
        $fileCollection = $trick->files();
        foreach($fileCollection as $file) {
            $currentFilePath = explode("/", $file->getPath());
            // remove param in path if exist.
            $currentFileName = explode("?", end($currentFilePath)) ? explode("?", end($currentFilePath))[0] : end($currentFilePath);
            if($currentFileName === $fileName) {
                $trick->removeFile($file);
                return true;
            }
        }
        return false;
    }
    #[Route("/create/trick/remove_file/{file_path}", name: "delete_on_creation_trick_file")]
    public function deleteOnCreationTrickFile(Request $request): ?Response
    {
        $trick = $request->getSession()->get("trick");
        $fileName = $request->attributes->get("file_path");
        //  We iterate through the files of the trick and check if the file name contained
        //  in the link (the file's path attribute) is the same as the one contained in the request. If it is, we remove it from the trick.
        $this->removeTrickFileByName($trick, $fileName);
        return $this->redirect("/create/trick");
    }
    #[Route("/edit/trick/remove_file/{file_path}", name: "delete_on_edition_trick_file")]
    public function deleteOnEditionTrickFile(Request $request): ?Response
    {
        $trick = $request->getSession()->get("trick_edition");
        $fileName = $request->attributes->get("file_path");
        //  We iterate through the files of the trick and check if the file name contained
        //  in the link (the file's path attribute) is the same as the one contained in the request. If it is, we remove it from the trick.
        $this->removeTrickFileByName($trick, $fileName);
        return $this->redirect("/edit/trick/".$trick->getSlug());
    }
    private function uploadSubmitedMediaFile(Trick $trick, array $params): void
    {
        foreach($params as $fieldName => $file) {
            switch($fieldName) {
                case 'image_file':
                    if($file) {
                        if(!$this->fileUploader->addUploadedFeaturedImageFile($file, $trick)) {
                            $this->addFlash('alert-warning', "Fichier non autorisé");
                        }
                    }
                    break;
                case "media_trick_file":
                    if($file) {
                        if(!$this->fileUploader->addUploadedTrickFile($file, $trick)) {
                            $this->addFlash('alert-warning', "Fichier non autorisé");
                        }
                    }
                    break;
            }
        }
    }
    private function uploadSubmitedMediaRef(Trick $trick, array $params): void
    {
        foreach($params as $fieldName => $ref) {
            switch($fieldName) {
                case "image_ref":
                    if($ref !== "") {
                        $this->fileUploader->addFeaturedImageRef($trick, $ref);
                    }
                break;
                case "media_ref":
                    if($ref !== "") {
                        $this->fileUploader->addTrickFileRef ($trick, $ref);
                    }
                    break;
                
            }
        }
    }
    
    #[Route("/delete/trick/{slug}", name: "delete_trick")]
    public function delete(Trick $trick): Response
    {
        if(!$this->denyAccessUnlessGranted('CAN_EDIT', $trick)) {
            $this->manageTrick->deleteTrick($trick);
            $this->addFlash('alert-success', "La figure a bien été supprimé.");
        }
        else {
            $this->addFlash('alert-warning', "Non autorisé");
        }
        return $this->redirect("/");
    }
    
    #[Route("/create/trick", name:"creation")]
    #[IsGranted("CAN_CREATE")]
    public function create(Request $request): Response
    {
        $session = $request->getSession();
        $trick = $session->get("trick", new Trick);
        $trickDetailsForm = $this->createForm(TrickCreationFormType::class);
        $trickFileForm = $this->createForm(FileFormType::class);
        $newGroupeForm = $this->createForm(NewGroupeFormType::class);
        $newGroupeForm->handleRequest($request);
        if($newGroupeForm->isSubmitted() && $newGroupeForm->isValid()) {
            $this->addNewGroupe->addNewGroupe($newGroupeForm);
        }
        $trickFileForm->handleRequest($request);
        $trickDetailsForm->handleRequest($request);
        if ($trickFileForm->isSubmitted()) {
            // if we have "editTrick" in get param, files is edited
            // we delete old file (that is value in "editTrick")
            if($request->query->get("editTrick")) {
                $this->removeTrickFileByName($trick, $request->query->get('editTrick'));
            }
            $mediaRef = $request->request->all();
            $this->uploadSubmitedMediaRef($trick, $mediaRef);
            $mediaFile = $request->files->all();
        $this->uploadSubmitedMediaFile($trick, $mediaFile);
                
        }
        if ($trickDetailsForm->isSubmitted() && $trickDetailsForm->isValid()) {
            $data = $trickDetailsForm->getData();
            return $this->manageTrick->saveNewTrick($trick, $data, $session);
        }
        $session->set("trick", $trick);
        return $this->render("tricks_management/create_trick.html.twig", [
            "detailsForm" => $trickDetailsForm,
            'mediaForm' => $trickFileForm->createView(),
            "trickFile" => $trickFileForm->createView(),
            "newGroupe" => $newGroupeForm->createView(), 
            "trick" => $trick
        ]);
    }
    #[Route("/edit/trick/{slug}", name:"trick_edit")]
    public function edit(Request $request, Trick $editTrick): Response
    {
        $this->denyAccessUnlessGranted('CAN_EDIT', $editTrick, "Non autorisé");
        $session = $request->getSession();
        $trick = $session->get("trick_edition", $editTrick);
        if($trick->getId() !== $editTrick->getId()) {
            $trick = $editTrick;
        }
        $trickDetailsForm = $this->createForm(TrickCreationFormType::class);
        $trickFileForm = $this->createForm(FileFormType::class);
        $newGroupeForm = $this->createForm(NewGroupeFormType::class);
        $newGroupeForm->handleRequest($request);
        if($newGroupeForm->isSubmitted() && $newGroupeForm->isValid()) {
            $this->addNewGroupe->addNewGroupe($newGroupeForm);
        }
        $trickFileForm->handleRequest($request);
        $trickDetailsForm->handleRequest($request);
        if ($trickFileForm->isSubmitted()) {
            // if we have "editTrick" in get param, files is edited
            // we delete old file (that is value in "editTrick")
            if($request->query->get("editTrick")) {
                $this->removeTrickFileByName($trick, $request->query->get('editTrick'));
            }
            $mediaRef = $request->request->all();
            $this->uploadSubmitedMediaRef($trick, $mediaRef);
            $mediaFile = $request->files->all();
            $this->uploadSubmitedMediaFile($trick, $mediaFile);

        }
        if ($trickDetailsForm->isSubmitted() && $trickDetailsForm->isValid()) {
            $data = $trickDetailsForm->getData();
            return $this->manageTrick->updateTrick($trick, $data, $session);
        }
        $session->set("trick_edition", $trick);
        //
        return $this->render("tricks_management/edit_trick_form.html.twig", [
            "detailsForm" => $trickDetailsForm,
            'mediaForm' => $trickFileForm->createView(),
            "trickFile" => $trickFileForm->createView(),
            "newGroupe" => $newGroupeForm->createView(),
            "trick" => $trick
        ]);
    }
}
