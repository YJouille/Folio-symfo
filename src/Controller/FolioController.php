<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;




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
     * @Route("/folio/new", name="folio_create")
     * @Route("/folio/{id}/edit", name="folio_edit")
     */
    //create or update form
    public function form(Project $project = null, Request $request, ManagerRegistry $doctrine )
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
