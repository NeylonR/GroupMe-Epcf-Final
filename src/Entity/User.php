<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse email est déjà utilisé.')]
#[UniqueEntity(fields: ['name'], message: 'Ce nom d\'utilisateur est déjà utilisé.')]
/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Length(
        min: 4,
        max: 60,
        minMessage: 'Votre email doit avoir un minimum de {{ limit }} caractères',
        maxMessage: 'Votre email doit faire moins de {{ limit }} caractères',
    )]
    #[Assert\Email(
        message: 'Veuillez rentrer une adresse email valide.',
    )]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\Length(
        min: 6,
        max: 60,
        minMessage: 'Votre mot de passe doit avoir un minimum de {{ limit }} caractères',
        maxMessage: 'Votre mot de passe doit faire moins de {{ limit }} caractères',
    )]
    private $password;
    
    #[ORM\Column(type: 'string', length: 41)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]{1,20}[ ]{0,1}[a-zA-Z0-9]{1,20}$/',
        message: 'Veuillez renseigner un nom d\'utilisateur valide.',
    )]
    #[Assert\Length(
        min: 4,
        max: 41,
        minMessage: 'Votre nom d\'utilisateur doit avoir un minimum de {{ limit }} caractères',
        maxMessage: 'Votre nom d\'utilisateur doit faire moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $discordTag;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $avatar;

    /** 
     * @Vich\UploadableField(mapping= "avatar_user", fileNameProperty="avatar")
     */
    #[Assert\File(
        maxSize: '2024k',
        mimeTypes: ["image/jpg", "image/jpeg", "image/png"],
        mimeTypesMessage: 'Veuillez sélectionner un format d\'image valide ( jpg/jpeg/png)',
        )]
    private $avatarFile;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: RecruitAdvertisement::class, cascade: ['persist', 'remove'])]
    private $recruitAdvertisement;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: PlayerAdvertisement::class, cascade: ['persist', 'remove'])]
    private $playerAdvertisement;

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscordTag(): ?string
    {
        return $this->discordTag;
    }

    public function setDiscordTag(?string $discordTag): self
    {
        $this->discordTag = $discordTag;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?File $avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;
 
        if($avatarFile !== null){
            $this->updatedAt = new \DateTime();
        }

        return;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRecruitAdvertisement(): ?RecruitAdvertisement
    {
        return $this->recruitAdvertisement;
    }

    public function setRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        // set the owning side of the relation if necessary
        if ($recruitAdvertisement->getOwner() !== $this) {
            $recruitAdvertisement->setOwner($this);
        }

        $this->recruitAdvertisement = $recruitAdvertisement;

        return $this;
    }

    public function getPlayerAdvertisement(): ?PlayerAdvertisement
    {
        return $this->playerAdvertisement;
    }

    public function setPlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        // set the owning side of the relation if necessary
        if ($playerAdvertisement->getOwner() !== $this) {
            $playerAdvertisement->setOwner($this);
        }

        $this->playerAdvertisement = $playerAdvertisement;

        return $this;
    }
}
