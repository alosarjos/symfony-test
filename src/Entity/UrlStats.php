<?php

namespace App\Entity;

use App\Repository\UrlStatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlStatsRepository::class)
 */
class UrlStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UrlEntry::class, inversedBy="urlStats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceType;

    /**
     * @ORM\Column(type="bigint")
     */
    private $accessTimestamp;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?UrlEntry
    {
        return $this->url;
    }

    public function setUrl(?UrlEntry $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function setDeviceType(string $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function getAccessTimestamp(): ?string
    {
        return $this->accessTimestamp;
    }

    public function setAccessTimestamp(string $accessTimestamp): self
    {
        $this->accessTimestamp = $accessTimestamp;

        return $this;
    }
}
