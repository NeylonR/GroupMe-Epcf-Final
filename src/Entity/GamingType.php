<?php

namespace App\Entity;

use App\Repository\GamingTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamingTypeRepository::class)]
class GamingType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'rosterType', targetEntity: RecruitAdvertisement::class)]
    private $recruitAdvertisement;

    #[ORM\OneToMany(mappedBy: 'playerType', targetEntity: PlayerAdvertisement::class)]
    private $playerAdvertisements;

    public function __construct()
    {
        $this->recruitAdvertisement = new ArrayCollection();
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
    public function getRecruitAdvertisement(): Collection
    {
        return $this->recruitAdvertisement;
    }

    public function addRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisementRecruitAdvertisement): self
    {
        if (!$this->recruitAdvertisement->contains($recruitAdvertisementRecruitAdvertisement)) {
            $this->recruitAdvertisement[] = $recruitAdvertisementRecruitAdvertisement;
            $recruitAdvertisementRecruitAdvertisement->setRosterType($this);
        }

        return $this;
    }

    public function removeRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisementRecruitAdvertisement): self
    {
        if ($this->recruitAdvertisement->removeElement($recruitAdvertisementRecruitAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($recruitAdvertisementRecruitAdvertisement->getRosterType() === $this) {
                $recruitAdvertisementRecruitAdvertisement->setRosterType(null);
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
            $playerAdvertisement->setPlayerType($this);
        }

        return $this;
    }

    public function removePlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($playerAdvertisement->getPlayerType() === $this) {
                $playerAdvertisement->setPlayerType(null);
            }
        }

        return $this;
    }
}
