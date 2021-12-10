<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      for ($i=1; $i <= 10 ; $i++) { 
          $project = new Project();
          $project->setTitle("Titre du projet n°$i")
                  ->setDescription("<p>Description du projet n°$i</p>")
                  ->setImage("http://placehold.it/350x150")
                  ->setGithub("Github du projet n°$i")
                  ->setWeblink("Weblink du projet n°$i");
                  $manager->persist($project);
      }

        $manager->flush();
    }
}
