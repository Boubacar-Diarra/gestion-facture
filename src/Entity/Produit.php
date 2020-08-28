<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=CmdProduit::class,cascade={"persist", "remove"}, mappedBy="client")
     */
    private $commandeProduits;

    /**
     * @ORM\OneToMany(targetEntity=ProduitFournisseur::class,cascade={"persist", "remove"}, mappedBy="produit")
     */
    private $produitFournisseurs;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteStock;

    private $prix_achat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    private $frequence;

    /**
     * @return mixed
     */
    public function getFrequence()
    {
        return $this->frequence;
    }

    /**
     * @param mixed $frequence
     * @return Produit
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;
        return $this;
    }

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
        $this->produitFournisseurs = new ArrayCollection();
        $this->created_at = new \DateTime();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @return Collection|CmdProduit[]
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CmdProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CmdProduit $commandeProduit): self
    {
        if ($this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->removeElement($commandeProduit);
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
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
            $produitFournisseur->setProduit($this);
        }

        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): self
    {
        if ($this->produitFournisseurs->contains($produitFournisseur)) {
            $this->produitFournisseurs->removeElement($produitFournisseur);
            // set the owning side to null (unless already changed)
            if ($produitFournisseur->getProduit() === $this) {
                $produitFournisseur->setProduit(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): self
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrixAchat()
    {
        return $this->prix_achat;
    }

    /**
     * @param mixed $prix_achat
     * @return Produit
     */
    public function setPrixAchat($prix_achat)
    {
        $this->prix_achat = $prix_achat;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    private $qte_temp;

    /**
    * @return mixed
    */
        public function getQteTemp()
        {
            return $this->qte_temp;
        }
    /**
     * @param mixed $qte_temp
     * @return Produit
     */
    public function setQteTemp($qte_temp)
    {
        $this->qte_temp = $qte_temp;
        return $this;
    }

}
