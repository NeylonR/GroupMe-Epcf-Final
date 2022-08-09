<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: DataCenter::class, orphanRemoval: true)]
    private $dataCenters;

    public function __construct()
    {
        $this->dataCenters = new ArrayCollection();
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
     * @return Collection<int, DataCenter>
     */
    public function getDataCenters(): Collection
    {
        return $this->dataCenters;
    }

    public function addDataCenter(DataCenter $dataCenter): self
    {
        if (!$this->dataCenters->contains($dataCenter)) {
            $this->dataCenters[] = $dataCenter;
            $dataCenter->setRegion($this);
        }

        return $this;
    }

    public function removeDataCenter(DataCenter $dataCenter): self
    {
        if ($this->dataCenters->removeElement($dataCenter)) {
            // set the owning side to null (unless already changed)
            if ($dataCenter->getRegion() === $this) {
                $dataCenter->setRegion(null);
            }
        }

        return $this;
    }
}
