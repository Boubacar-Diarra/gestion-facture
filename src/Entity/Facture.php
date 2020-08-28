<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    public const TOTALITE = "Payer en sa totalité";
    public const PARTIELLEMENT = "Partiellement";
    public const NONPAYER = "Non payer";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, cascade={"persist", "remove"}, mappedBy="facture")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=Cheque::class, mappedBy="facture")
     */
    private $cheque;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant_restant;

    /**
     * @ORM\OneToMany(targetEntity=InfoPaiementEspece::class, mappedBy="facture")
     */
    private $infoPaiementEspece;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->cheque = new ArrayCollection();
        $this->infoPaiementEspece = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setFacture($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getFacture() === $this) {
                $commande->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cheque[]
     */
    public function getCheque(): Collection
    {
        return $this->cheque;
    }

    public function addCheque(Cheque $cheque): self
    {
        if (!$this->cheque->contains($cheque)) {
            $this->cheque[] = $cheque;
            $cheque->setFacture($this);
        }

        return $this;
    }

    public function removeCheque(Cheque $cheque): self
    {
        if ($this->cheque->contains($cheque)) {
            $this->cheque->removeElement($cheque);
            // set the owning side to null (unless already changed)
            if ($cheque->getFacture() === $this) {
                $cheque->setFacture(null);
            }
        }

        return $this;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montant_restant;
    }

    public function setMontantRestant(?float $montant_restant): self
    {
        $this->montant_restant = $montant_restant;

        return $this;
    }

    /**
     * @return Collection|InfoPaiementEspece[]
     */
    public function getInfoPaiementEspece(): Collection
    {
        return $this->infoPaiementEspece;
    }

    public function addInfoPaiementEspece(InfoPaiementEspece $infoPaiementEspece): self
    {
        if (!$this->infoPaiementEspece->contains($infoPaiementEspece)) {
            $this->infoPaiementEspece[] = $infoPaiementEspece;
            $infoPaiementEspece->setFacture($this);
        }

        return $this;
    }

    public function removeInfoPaiementEspece(InfoPaiementEspece $infoPaiementEspece): self
    {
        if ($this->infoPaiementEspece->contains($infoPaiementEspece)) {
            $this->infoPaiementEspece->removeElement($infoPaiementEspece);
            // set the owning side to null (unless already changed)
            if ($infoPaiementEspece->getFacture() === $this) {
                $infoPaiementEspece->setFacture(null);
            }
        }

        return $this;
    }
}
