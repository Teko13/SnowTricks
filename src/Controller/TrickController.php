<?php
namespace App\Controller;
use App\Entity\Trick;
use App\Form\FileFormType;
use App\Form\TrickCreationFormType;
use App\SaveNewTrick\SaveNewTrick;
use App\UploadFile\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TrickController extends AbstractController {
    private SaveNewTrick $newTrickSaver;
    private UploadFile $fileUploader;
    public function __construct(SaveNewTrick $newTrickSaver, UploadFile $fileUploader) {
        $this->fileUploader = $fileUploader;
        $this->newTrickSaver = $newTrickSaver;
    }

    #[Route('/trick/{slug}',name: "trick_show")]
    function show(Trick $trick): Response
    {
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
    private function removeTrickFileByName(Trick $trick, string $fileName): bool
    {
        $fileCollection = $trick->getFiles();
        foreach($fileCollection as $file) {
            if(basename($file->getPath()) === $fileName) {
                $trick->removeFile($file);
                return true;
            }
        }
        return false;
    }
    #[Route("/create/trick/remove_file/{file_path}", name: "delete_on_creation_trick_file")]
    public function deleteOnCreationTrickFile(Request $request): Response
    {
        $trick = $request->getSession()->get("trick");
        $fileName = $request->attributes->get("file_path");
        //  We iterate through the files of the trick and check if the file name contained
        //  in the link (the file's path attribute) is the same as the one contained in the request. If it is, we remove it from the trick.
        $this->removeTrickFileByName($trick, $fileName);
        return $this->redirect("/create/trick");
    }
    private function uploadSubmitedMediaFile(Trick $trick, array $params): void
    {
        foreach($params as $fieldName => $file) {
            switch($fieldName) {
                case 'featured_image_file':
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
                case "featured_image_ref":
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
    #[Route("/edit/trick/{id}", name:"trick_edit")]
    public function edit(Trick $trick): Response
    {
        $this->denyAccessUnlessGranted('CAN_EDIT', $trick, "Vous n'avez pas le droit d'accéder à cette ressource");
        return new Response("");
    }
    #[Route("/create/trick", name:"creation")]
    #[IsGranted("CAN_CREATE")]
    public function create(Request $request): Response
    {
        $session = $request->getSession();
        $trick = $session->get("trick", new Trick);
        $trickDetailsForm = $this->createForm(TrickCreationFormType::class);
        $trickFileForm = $this->createForm(FileFormType::class);
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
            return $this->newTrickSaver->saveNewTrick($trick, $data, $session);
        }
        $session->set("trick", $trick);
         //dd($request->files);
        
        return $this->render("tricks_management/trick_creation.html.twig", [
            "detailsForm" => $trickDetailsForm,
            'featuredMedia' => $trickFileForm->createView(),
            "trickFile" => $trickFileForm->createView(),
            "trick" => $trick
        ]);
    } 
}
