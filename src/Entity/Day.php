<?php

namespace App\Entity;

use App\Repository\DayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DayRepository::class)]
class Day
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\ManyToMany(targetEntity: RecruitAdvertisement::class, mappedBy: 'day')]
    private $recruitAdvertisements;

    #[ORM\ManyToMany(targetEntity: PlayerAdvertisement::class, mappedBy: 'day')]
    private $playerAdvertisements;

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
            $recruitAdvertisement->addDay($this);
        }

        return $this;
    }

    public function removeRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if ($this->recruitAdvertisements->removeElement($recruitAdvertisement)) {
            $recruitAdvertisement->removeDay($this);
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
            $playerAdvertisement->addDay($this);
        }

        return $this;
    }

    public function removePlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
            $playerAdvertisement->removeDay($this);
        }

        return $this;
    }
}
