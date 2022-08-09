<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\RecruitAdvertisementRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecruitAdvertisementRepository::class)]
/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class RecruitAdvertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'recruitAdvertisement', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    #[ORM\ManyToOne(targetEntity: DataCenter::class, inversedBy: 'recruitAdvertisements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $dataCenter;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'recruitAdvertisements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $language;

    #[ORM\ManyToMany(targetEntity: Day::class, inversedBy: 'recruitAdvertisements')]
    #[Assert\Count(
        min: 1,
        max: 7,
        minMessage: 'Vous devez sélectionner au moins {{ limit }} jour',
        maxMessage: 'Vous ne pouvez pas sélectionner plus de {{ limit }} jours',
    )]
    private $day;


    #[ORM\ManyToOne(targetEntity: GamingType::class, inversedBy: 'recruitAdvertisements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $rosterType;

    #[ORM\Column(type: 'string', length: 40)]
    #[Assert\Length(
        min: 4,
        max: 30,
        minMessage: 'Votre nom de roster doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre nom de roster doit faire moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $rosterName;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(
        min: 500,
        max: 650,
        notInRangeMessage: 'Vous devez renseignez un nombre entre {{ min }} et {{ max }}'
    )]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $ilvl;

    #[ORM\Column(type: 'text')]
    #[Assert\Length(
        min: 20,
        max: 500,
        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre description doit faire moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $textContent;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^https:\/\/discord\.gg\/[a-zA-Z0-9]{1,20}$/',
        message: 'Veuillez renseigner un lien valide.',
    )]
    private $discordServerLink;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^https:\/\/www\.fflogs\.com\/character\/id\/[0-9]+/',
        message: 'Veuillez renseigner un lien valide.',
    )]
    private $fflogLink;

    #[ORM\Column(type: 'time')]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $activityStart;

    #[ORM\Column(type: 'time')]
    #[Assert\NotBlank(
        message: 'Ce champ ne peut pas être envoyé vide.'
    )]
    private $activityEnd;

    #[ORM\Column(type: 'string', length: 255)]
    private $banner;

    /** 
    * @Vich\UploadableField(mapping= "banner_roster", fileNameProperty="banner")
    */
    #[Assert\File(
    maxSize: '2024k',
    mimeTypes: ["image/jpg", "image/jpeg", "image/png"],
    mimeTypesMessage: 'Veuillez sélectionner un format d\'image valide ( jpg/jpeg/png)',
    )]
    private $bannerFile;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToMany(targetEntity: Job::class, inversedBy: 'recruitAdvertisements')]
    private $job;

    public function __construct()
    {
        $this->day = new ArrayCollection();
        $this->job = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDataCenter(): ?DataCenter
    {
        return $this->dataCenter;
    }

    public function setDataCenter(?DataCenter $dataCenter): self
    {
        $this->dataCenter = $dataCenter;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, Day>
     */
    public function getDay(): Collection
    {
        return $this->day;
    }

    public function addDay(Day $day): self
    {
        if (!$this->day->contains($day)) {
            $this->day[] = $day;
        }

        return $this;
    }

    public function removeDay(Day $day): self
    {
        $this->day->removeElement($day);

        return $this;
    }

    public function getRosterType(): ?GamingType
    {
        return $this->rosterType;
    }

    public function setRosterType(?GamingType $rosterType): self
    {
        $this->rosterType = $rosterType;

        return $this;
    }

    public function getRosterName(): ?string
    {
        return $this->rosterName;
    }

    public function setRosterName(?string $rosterName): self
    {
        $this->rosterName = $rosterName;

        return $this;
    }

    public function getIlvl(): ?int
    {
        return $this->ilvl;
    }

    public function setIlvl(?int $ilvl): self
    {
        $this->ilvl = $ilvl;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(?string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    public function getDiscordServerLink(): ?string
    {
        return $this->discordServerLink;
    }

    public function setDiscordServerLink(?string $discordServerLink): self
    {
        $this->discordServerLink = $discordServerLink;

        return $this;
    }

    public function getFflogLink(): ?string
    {
        return $this->fflogLink;
    }

    public function setFflogLink(?string $fflogLink): self
    {
        $this->fflogLink = $fflogLink;

        return $this;
    }

    public function getActivityStart(): ?\DateTimeInterface
    {
        return $this->activityStart;
    }

    public function setActivityStart(\DateTimeInterface $activityStart): self
    {
        $this->activityStart = $activityStart;

        return $this;
    }

    public function getActivityEnd(): ?\DateTimeInterface
    {
        return $this->activityEnd;
    }

    public function setActivityEnd(\DateTimeInterface $activityEnd): self
    {
        $this->activityEnd = $activityEnd;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getBannerFile(): ?File
    {
        return $this->bannerFile;
    }

    public function setBannerFile(?File $bannerFile = null): void
    {
        $this->bannerFile = $bannerFile;
 
        if($bannerFile !== null){
            $this->updatedAt = new \DateTime();
        }

        return;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJob(): Collection
    {
        return $this->job;
    }

    public function addJob(Job $job): self
    {
        if (!$this->job->contains($job)) {
            $this->job[] = $job;
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        $this->job->removeElement($job);

        return $this;
    }
}
