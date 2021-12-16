<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
// use PhpParser\Node\Stmt\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @Vich\Uploadable
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit comporter 10 caractères minimum")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="La description doit comporter 10 caractères minimum")
     */
    private $description;

     /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $filename;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="project_image", fileNameProperty="filename")
     * @Assert\Image(
     * mimeTypes="image/jpeg", mimeTypesMessage = "Le format de l'image n'est pas valide, seul le fomat jpeg est accepté")
     * @var File|null
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message = "Le format de l'URL est non valide")
     */
    private $github;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "Le format de l'URL est non valide")
     */
    private $weblink;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="projects")
     */
    private $categories;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getWeblink(): ?string
    {
        return $this->weblink;
    }

    public function setWeblink(string $weblink): self
    {
        $this->weblink = $weblink;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     * @return Project
     */
    public function setFilename(?string $filename): Project
    {
        $this->filename = $filename;
        return $this;

    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Project
     */
    public function setImageFile(?File $imageFile): Project
    {
        $this->imageFile = $imageFile;

        // Only change the updated af if the file is really uploaded to avoid database updates.
        // This is needed when the file should be set when loading the entity.
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    
}
