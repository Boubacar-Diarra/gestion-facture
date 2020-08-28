<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, mappedBy="fournisseurs")
     */
    private $entreprises;

    /**
     * @ORM\OneToMany(targetEntity=ProduitFournisseur::class, mappedBy="fournisseur")
     */
    private $produitFournisseurs;

    public function __construct()
    {
        $this->entreprises = new ArrayCollection();
        $this->produitFournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->addFournisseur($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->contains($entreprise)) {
            $this->entreprises->removeElement($entreprise);
            $entreprise->removeFournisseur($this);
        }

        return $this;
    }

    /**
     * @return Collection|ProduitFournisseur[]
     */
    public function getProduitFournisseurs(): Collection
    {
        return $this->produitFournisseurs;
    }

    public function addProduitFournisseur(ProduitFournisseur $produitFournisseur): self
    {
        if (!$this->produitFournisseurs->contains($produitFournisseur)) {
            $this->produitFournisseurs[] = $produitFournisseur;
            $produitFournisseur->setFournisseur($this);
        }

        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): self
    {
        if ($this->produitFournisseurs->contains($produitFournisseur)) {
            $this->produitFournisseurs->removeElement($produitFournisseur);
            // set the owning side to null (unless already changed)
            if ($produitFournisseur->getFournisseur() === $this) {
                $produitFournisseur->setFournisseur(null);
            }
        }

        return $this;
    }
}
