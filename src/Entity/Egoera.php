<?php

namespace App\Entity;

use App\Repository\EgoeraRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EgoeraRepository::class)
 */
class Egoera
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
    private $izena;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/

    public function __toString()
    {
        return (string)$this->getIzena();
    }

    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/
    /***********************************************************************************************************************************************************/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIzena(): ?string
    {
        return $this->izena;
    }

    public function setIzena(string $izena): self
    {
        $this->izena = $izena;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
