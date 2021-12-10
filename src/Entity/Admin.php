<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @UniqueEntity(
 * fields = {"username"},
 * message = "Le nom utilisateur est déjà utilisé !"
 * )
 * 
 */
class Admin implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255) 
     * @Assert\Length(min=8, max=255, minMessage="Le nom d'utilisateur doit comporter 8 caractères minimum")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit comporter 8 caractères minimum") 
     */
    private $password;

    // champ de l'entité mais qui n'existe pas dans la BDD
    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas saisi le même mot de passe")
     */
    private $confirmPassword;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    // a supprimer car dans la base et pas dans le formulaire?
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

// a supprimer car dans la base et pas dans le formulaire?
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSalt()
    {
        
    }
    public function getRoles()
    {
        //Pour le moment un seul role admin
        return ['ROLE_ADMIN'];
    }
}
