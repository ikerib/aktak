<?php

namespace App\Entity;

use App\Repository\ErabakiaRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=ErabakiaRepository::class)
 * @Vich\Uploadable()
 */
class Erabakia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $adata;

    /**
     * @ORM\Column(type="text")
     */
    private $gaiak;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $temas;

    /**
     * @Vich\UploadableField(mapping="pdf_mapping", fileNameProperty="url")
     * @var File
     */
    private $pdfFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $oharrak;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $akta;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity=Liburua::class, inversedBy="erabakias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liburua;

    public function __toString()
    {
        return (string)$this->getGaiak();
    }

    public function setPdfFile(File $image = null)
    {
        $this->pdfFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdata(): ?\DateTimeInterface
    {
        return $this->adata;
    }

    public function setAdata(\DateTimeInterface $adata): self
    {
        $this->adata = $adata;

        return $this;
    }

    public function getGaiak(): ?string
    {
        return $this->gaiak;
    }

    public function setGaiak(string $gaiak): self
    {
        $this->gaiak = $gaiak;

        return $this;
    }

    public function getTemas(): ?string
    {
        return $this->temas;
    }

    public function setTemas(?string $temas): self
    {
        $this->temas = $temas;

        return $this;
    }

    public function getUrl(): ?string
    {
        // Aurreko bertsioan url guztiak /-rekin hasten ziren, eta vichuploadbundle erabiltzean URL-an // gehitzen zuen. Honekin hori ekiditen da.
        if ($this->url) {
            if ( ( $this->url !== '' ) && $this->url[ 0 ] === "/" ) {
                return substr( $this->url, 1 );
            }
        }

        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getLiburua(): ?Liburua
    {
        return $this->liburua;
    }

    public function setLiburua(?Liburua $liburua): self
    {
        $this->liburua = $liburua;

        return $this;
    }

    public function getAkta(): ?bool
    {
        return $this->akta;
    }

    public function setAkta(?bool $akta): self
    {
        $this->akta = $akta;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
