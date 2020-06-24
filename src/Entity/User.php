<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $deparment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $displayname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $dn;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $hizkuntza;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $lanpostua;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private ?string $ldapsaila;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups("main")
     */
    private ?string $nan;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("main")
     */
    private ?bool $sailburuada;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $surname;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $ldapTaldeak = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $ldapRolak = [];

    /************************************************************************************************************************************************************/
    /************************************************************************************************************************************************************/
    /************************************************************************************************************************************************************/

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
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
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDeparment(): ?string
    {
        return $this->deparment;
    }

    public function setDeparment(?string $deparment): self
    {
        $this->deparment = $deparment;

        return $this;
    }

    public function getDisplayname(): ?string
    {
        return $this->displayname;
    }

    public function setDisplayname(?string $displayname): self
    {
        $this->displayname = $displayname;

        return $this;
    }

    public function getDn(): ?string
    {
        return $this->dn;
    }

    public function setDn(?string $dn): self
    {
        $this->dn = $dn;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getHizkuntza(): ?string
    {
        return $this->hizkuntza;
    }

    public function setHizkuntza(?string $hizkuntza): self
    {
        $this->hizkuntza = $hizkuntza;

        return $this;
    }

    public function getLanpostua(): ?string
    {
        return $this->lanpostua;
    }

    public function setLanpostua(?string $lanpostua): self
    {
        $this->lanpostua = $lanpostua;

        return $this;
    }

    public function getLdapsaila(): ?string
    {
        return $this->ldapsaila;
    }

    public function setLdapsaila(?string $ldapsaila): self
    {
        $this->ldapsaila = $ldapsaila;

        return $this;
    }

    public function getNan(): ?string
    {
        return $this->nan;
    }

    public function setNan(?string $nan): self
    {
        $this->nan = $nan;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getSailburuada(): ?bool
    {
        return $this->sailburuada;
    }

    public function setSailburuada(?bool $sailburuada): self
    {
        $this->sailburuada = $sailburuada;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getLdapTaldeak(): ?array
    {
        return $this->ldapTaldeak;
    }

    public function setLdapTaldeak(?array $ldapTaldeak): self
    {
        $this->ldapTaldeak = $ldapTaldeak;

        return $this;
    }

    public function getLdapRolak(): ?array
    {
        return $this->ldapRolak;
    }

    public function setLdapRolak(?array $ldapRolak): self
    {
        $this->ldapRolak = $ldapRolak;

        return $this;
    }


}
