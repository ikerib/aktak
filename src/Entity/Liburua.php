<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LiburuaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LiburuaRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Liburua
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
    private $kodea;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $hasiera;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $amaiera;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orriak;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $altuera;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zabalera;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $katalogatua;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $oharrak;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\OneToMany(targetEntity=Erabakia::class, mappedBy="liburua", orphanRemoval=true)
     */
    private $erabakias;

    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity=Egoera::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $egoera;

    /**
     * @ORM\ManyToOne(targetEntity=Mota::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $mota;

    public function __construct()
    {
        $this->erabakias = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getKodea();
    }
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKodea(): ?string
    {
        return $this->kodea;
    }

    public function setKodea(string $kodea): self
    {
        $this->kodea = $kodea;

        return $this;
    }

    public function getHasiera(): ?\DateTimeInterface
    {
        return $this->hasiera;
    }

    public function setHasiera(?\DateTimeInterface $hasiera): self
    {
        $this->hasiera = $hasiera;

        return $this;
    }

    public function getAmaiera(): ?\DateTimeInterface
    {
        return $this->amaiera;
    }

    public function setAmaiera(?\DateTimeInterface $amaiera): self
    {
        $this->amaiera = $amaiera;

        return $this;
    }

    public function getOrriak(): ?int
    {
        return $this->orriak;
    }

    public function setOrriak(?int $orriak): self
    {
        $this->orriak = $orriak;

        return $this;
    }

    public function getAltuera(): ?int
    {
        return $this->altuera;
    }

    public function setAltuera(?int $altuera): self
    {
        $this->altuera = $altuera;

        return $this;
    }

    public function getZabalera(): ?int
    {
        return $this->zabalera;
    }

    public function setZabalera(?int $zabalera): self
    {
        $this->zabalera = $zabalera;

        return $this;
    }

    public function getKatalogatua(): ?bool
    {
        return $this->katalogatua;
    }

    public function setKatalogatua(?bool $katalogatua): self
    {
        $this->katalogatua = $katalogatua;

        return $this;
    }

    public function getEgoera(): ?Egoera
    {
        return $this->egoera;
    }

    public function setEgoera(?Egoera $egoera): self
    {
        $this->egoera = $egoera;

        return $this;
    }

    public function getOharrak(): ?string
    {
        return $this->oharrak;
    }

    public function setOharrak(?string $oharrak): self
    {
        $this->oharrak = $oharrak;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getMota(): ?Mota
    {
        return $this->mota;
    }

    public function setMota(?Mota $mota): self
    {
        $this->mota = $mota;

        return $this;
    }

    /**
     * @return Collection|Erabakia[]
     */
    public function getErabakias(): Collection
    {
        return $this->erabakias;
    }

    public function addErabakia(Erabakia $erabakia): self
    {
        if (!$this->erabakias->contains($erabakia)) {
            $this->erabakias[] = $erabakia;
            $erabakia->setLiburua($this);
        }

        return $this;
    }

    public function removeErabakia(Erabakia $erabakia): self
    {
        if ($this->erabakias->contains($erabakia)) {
            $this->erabakias->removeElement($erabakia);
            // set the owning side to null (unless already changed)
            if ($erabakia->getLiburua() === $this) {
                $erabakia->setLiburua(null);
            }
        }

        return $this;
    }
}
