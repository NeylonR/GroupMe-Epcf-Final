<?php

namespace App\Entity;

use App\Repository\HomeWorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HomeWorldRepository::class)]
class HomeWorld
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\ManyToOne(targetEntity: DataCenter::class, inversedBy: 'homeWorlds')]
    #[ORM\JoinColumn(nullable: false)]
    private $dataCenter;

    #[ORM\OneToMany(mappedBy: 'homeWorld', targetEntity: PlayerAdvertisement::class)]
    private $playerAdvertisements;

    public function __construct()
    {
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

    public function getDataCenter(): ?DataCenter
    {
        return $this->dataCenter;
    }

    public function setDataCenter(?DataCenter $dataCenter): self
    {
        $this->dataCenter = $dataCenter;

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
            $playerAdvertisement->setHomeWorld($this);
        }

        return $this;
    }

    public function removePlayerAdvertisement(PlayerAdvertisement $playerAdvertisement): self
    {
        if ($this->playerAdvertisements->removeElement($playerAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($playerAdvertisement->getHomeWorld() === $this) {
                $playerAdvertisement->setHomeWorld(null);
            }
        }

        return $this;
    }
}
