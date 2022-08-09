<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'language', targetEntity: RecruitAdvertisement::class)]
    private $recruitAdvertisements;

    #[ORM\OneToMany(targetEntity: PlayerAdvertisement::class, mappedBy: 'language')]
    private $playerAdvertisements;

    #[ORM\Column(type: 'string', length: 255)]
    private $icon;

    /** 
     * @Vich\UploadableField(mapping= "language_icon", fileNameProperty="icon")
     */
    private $iconFile;

    public function __construct()
    {
        $this->recruitAdvertisements = new ArrayCollection();
        $this->playerAdvertisements = new ArrayCollection();
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
            $recruitAdvertisement->setLanguage($this);
        }

        return $this;
    }

    public function removeRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if ($this->recruitAdvertisements->removeElement($recruitAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($recruitAdvertisement->getLanguage() === $this) {
                $recruitAdvertisement->setLanguage(null);
            }
        }

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
            $playerAdvertisement->setLanguage($this);
        }

        return $this;
    }

    public function removePlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        // if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
        //     $playerAdvertisement->removeLanguage($this);
        // }
        if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($playerAdvertisement->getLanguage() === $this) {
                $playerAdvertisement->setLanguage(null);
            }
        }
        return $this;
    }

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
}
