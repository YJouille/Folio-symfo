<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Category;
use App\Form\ProjectType;
use App\Form\CategoryType;
use App\Repository\ProjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FolioController extends AbstractController
{
    /**
     * @Route("/folio", name="folio")
     */
    public function index(ProjectRepository $repo): Response

    {
        // $repo = $this->getDoctrine()->getRepository(Project::class);
        // plus besoin de la ligne ci-haut, symfony s'occupe des dépendances grace à l'injection
        // $repo est passé à présent comme paramètre de la méthode index
        $projects = $repo->findAll();

        return $this->render('folio/index.html.twig', [
            'controller_name' => 'FolioController',
            'projects' => $projects
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('folio/home.html.twig', [
            'title' => "Bienvenue",
            'age' => 31
        ]);
    }
    /**
     * @Route("/folio/newCategory", name="new_category")
     */
    public function newCategory(Category $category = null, Request $request, ManagerRegistry $doctrine ){
        $manager = $doctrine->getManager();
        if (!$category) {
            $category = new Category();//categorie vide par defaut
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($category);
           $manager->flush();

           return $this->redirectToRoute('folio');
        }

        return $this->render('folio/newCategory.html.twig',[
            'formCategory' => $form->createView(),            
        ]);     
    }
    
    /**
     * @Route("/folio/new", name="folio_create")
     * @Route("/folio/{id}/edit", name="folio_edit")
     */
    //create or update form

    public function form(Project $project = null, Request $request, ManagerRegistry $doctrine)
    {
        $manager = $doctrine->getManager();
        if (!$project) {
            $project = new Project();//article vide par defaut
        }
     
       
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($project);
           $manager->flush();

           return $this->redirectToRoute('folio', ['id' => $project->getId()]);
        }

        return $this->render('folio/create.html.twig',[
            'formProject' => $form->createView(),
            'editMode' => $project->getId() !== null 
        ]);
    }

//To delete!!!!!!!!!!!!!! + delete les imports
    
// public function form(Project $project = null, Request $request, ManagerRegistry $doctrine, CacheManager $cacheManager, UploaderHelper $helper )
// {
//     $manager = $doctrine->getManager();
//     if (!$project) {
//         $project = new Project();//article vide par defaut
//     }
 
   
//     $form = $this->createForm(ProjectType::class, $project);

//     $form->handleRequest($request);
//     if($project->getImageFile() instanceof UploadedFile){ //n'est donc pas une instance de File donc le fichier a été uploadé
//         //il faut donc supprimer l'image au niveau du cache
//         $cacheManager->remove($helper->asset($project,'imageFile'));
//     }

//     if ($form->isSubmitted() && $form->isValid()) {
//        $manager->persist($project);
//        $manager->flush();

//        return $this->redirectToRoute('folio', ['id' => $project->getId()]);
//     }

//     return $this->render('folio/create.html.twig',[
//         'formProject' => $form->createView(),
//         'editMode' => $project->getId() !== null 
//     ]);
// }

    /**
     * @Route("/folio/{id}/delete", name="folio_delete" )
     */
    public function delete(Project $project, ManagerRegistry $doctrine){
        $manager = $doctrine->getManager();

    //    $this->$manager->remove($project);
    //    $this->$manager->flush();

       $manager->remove($project);
       $manager->flush();
          
       return $this->redirectToRoute("folio");

    }

    /**
     * @Route("/folio/{id}", name="project_show")
     */
    // public function show(ProjectRepository $repo, $id){
    //     // $repo = $this->getDoctrine()->getRepository(Project::class);
    //     //idem que dans index
    //     $project = $repo->find($id);

    //     return $this->render('folio/show.html.twig',[
    //         'project' => $project
    //     ]);
    // }
    //grace au param converter show devient :
    //symfony fait les bonnes instantiations grace au service container
    public function show(Project $project)
    {
        return $this->render('folio/show.html.twig', [
            'project' => $project
        ]);
    }
    
}
