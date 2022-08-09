<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: JobRepository::class)]
/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    private $role;

    #[ORM\ManyToMany(targetEntity: PlayerAdvertisement::class, mappedBy: 'job')]
    private $playerAdvertisements;

    // #[ORM\ManyToMany(targetEntity: RecruitPlayerJob::class, mappedBy: 'job')]
    // private $recruitPlayerJobs;

    #[ORM\Column(type: 'string', length: 255)]
    private $icon;

    /** 
     * @Vich\UploadableField(mapping= "job_icon", fileNameProperty="icon")
     */
    private $iconFile;

    #[ORM\ManyToMany(targetEntity: RecruitAdvertisement::class, mappedBy: 'job')]
    private $recruitAdvertisements;

    public function __construct()
    {
        $this->playerAdvertisements = new ArrayCollection();
        // $this->recruitPlayerJobs = new ArrayCollection();
        $this->recruitAdvertisements = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, PlayerAdvertisement>
     */
    public function getPlayerAdvertisements(): Collection
    {
        return $this->playerAdvertisements;
    }

    public function addPlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        if (!$this->playerAdvertisements->contains($playerAdvertisement)) {
            $this->playerAdvertisements[] = $playerAdvertisement;
            $playerAdvertisement->addJob($this);
        }

        return $this;
    }

    public function removePlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
            $playerAdvertisement->removeJob($this);
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, RecruitPlayerJob>
    //  */
    // public function getRecruitPlayerJobs(): Collection
    // {
    //     return $this->recruitPlayerJobs;
    // }

    // public function addRecruitPlayerJob(RecruitPlayerJob $recruitPlayerJob): self
    // {
    //     if (!$this->recruitPlayerJobs->contains($recruitPlayerJob)) {
    //         $this->recruitPlayerJobs[] = $recruitPlayerJob;
    //         $recruitPlayerJob->addJob($this);
    //     }

    //     return $this;
    // }

    // public function removeRecruitPlayerJob(RecruitPlayerJob $recruitPlayerJob): self
    // {
    //     if ($this->recruitPlayerJobs->removeElement($recruitPlayerJob)) {
    //         $recruitPlayerJob->removeJob($this);
    //     }

    //     return $this;
    // }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIconFile(): ?File
    {
        return $this->iconFile;
    }

    public function setIconFile(?File $iconFile = null): void
    {
        $this->iconFile = $iconFile;
 
        if($iconFile !== null){
            $this->updatedAt = new \DateTime();
        }

        return;
    }

    /**
     * @return Collection<int, RecruitAdvertisement>
     */
    public function getRecruitAdvertisements(): Collection
    {
        return $this->recruitAdvertisements;
    }

    public function addRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if (!$this->recruitAdvertisements->contains($recruitAdvertisement)) {
            $this->recruitAdvertisements[] = $recruitAdvertisement;
            $recruitAdvertisement->addJob($this);
        }

        return $this;
    }

    public function removeRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if ($this->recruitAdvertisements->removeElement($recruitAdvertisement)) {
            $recruitAdvertisement->removeJob($this);
        }

        return $this;
    }
}
