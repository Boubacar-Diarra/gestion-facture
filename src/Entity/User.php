<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email",message="Cet émail est déjà utilisé")
 */
class User implements UserInterface
{
    const NOMREGEX = "/^([-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜA-Z\s\d]{1}[-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜa-z\s\d]{0,}){1,}$/";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^([-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜA-Z\s\d]{1}[-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜa-z\s\d]{0,}){1,}$/",
     *     match=true,
     *     message="Votre nom ne doit contenir que des lettres, des espace, des chiffires et avec une majuscule au début"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^([-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜA-Z\s\d]{1}[-'’éâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜa-z\s\d]{0,}){1,}$/",
     *     match=true,
     *     message="Votre nom ne doit contenir que des lettres, des espace, des chiffires et avec une majuscule au début"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    private $confirmation;

    /**
     * @return mixed
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @param mixed $confirmation
     */
    public function setConfirmation($confirmation): void
    {
        $this->confirmation = $confirmation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
