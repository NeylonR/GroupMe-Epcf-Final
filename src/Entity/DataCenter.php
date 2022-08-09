<?php

namespace App\Entity;

use App\Repository\DataCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataCenterRepository::class)]
class DataCenter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'dataCenter', targetEntity: HomeWorld::class, orphanRemoval: true)]
    private $homeWorlds;

    #[ORM\OneToMany(mappedBy: 'dataCenter', targetEntity: RecruitAdvertisement::class, orphanRemoval: true)]
    private $recruitAdvertisement;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'dataCenters')]
    #[ORM\JoinColumn(nullable: false)]
    private $region;

    public function __construct()
    {
        $this->homeWorlds = new ArrayCollection();
        $this->recruitAdvertisement = new ArrayCollection();
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
     * @return Collection<int, HomeWorld>
     */
    public function getHomeWorlds(): Collection
    {
        return $this->homeWorlds;
    }

    public function addHomeWorld(HomeWorld $homeWorld): self
    {
        if (!$this->homeWorlds->contains($homeWorld)) {
            $this->homeWorlds[] = $homeWorld;
            $homeWorld->setDataCenter($this);
        }

        return $this;
    }

    public function removeHomeWorld(HomeWorld $homeWorld): self
    {
        if ($this->homeWorlds->removeElement($homeWorld)) {
            // set the owning side to null (unless already changed)
            if ($homeWorld->getDataCenter() === $this) {
                $homeWorld->setDataCenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecruitAdvertisement>
     */
    public function getRecruitAdvertisement(): Collection
    {
        return $this->recruitAdvertisement;
    }

    public function addRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if (!$this->recruitAdvertisement->contains($recruitAdvertisement)) {
            $this->recruitAdvertisement[] = $recruitAdvertisement;
            $recruitAdvertisement->setDataCenter($this);
        }

        return $this;
    }

    public function removeRecruitAdvertisement(RecruitAdvertisement $recruitAdvertisement): self
    {
        if ($this->recruitAdvertisement->removeElement($recruitAdvertisement)) {
            // set the owning side to null (unless already changed)
            if ($recruitAdvertisement->getDataCenter() === $this) {
                $recruitAdvertisement->setDataCenter(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
