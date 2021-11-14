<?php

namespace App\Entity;

use App\Repository\UrlEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlEntryRepository::class)
 */
class UrlEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $realUrl;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $shortUrl;

    private $algorythm;

    /**
     * @ORM\OneToMany(targetEntity=UrlStats::class, mappedBy="url", orphanRemoval=true)
     */
    private $urlStats;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="urlEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->urlStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealUrl(): ?string
    {
        return $this->realUrl;
    }

    public function setRealUrl(string $realUrl): self
    {
        $realUrl = preg_replace('(^https?://)', '', $realUrl);

        $this->realUrl = $realUrl;

        return $this;
    }

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getAlgorythm(): ?string
    {
        return $this->algorythm;
    }

    public function setAlgorythm(string $shortUrl): self
    {
        $this->algorythm = $shortUrl;

        return $this;
    }

    /**
     * @return Collection|UrlStats[]
     */
    public function getUrlStats(): Collection
    {
        return $this->urlStats;
    }

    public function addUrlStat(UrlStats $urlStat): self
    {
        if (!$this->urlStats->contains($urlStat)) {
            $this->urlStats[] = $urlStat;
            $urlStat->setUrl($this);
        }

        return $this;
    }

    public function removeUrlStat(UrlStats $urlStat): self
    {
        if ($this->urlStats->removeElement($urlStat)) {
            // set the owning side to null (unless already changed)
            if ($urlStat->getUrl() === $this) {
                $urlStat->setUrl(null);
            }
        }

        return $this;
    }

    public function getCounter(): int
    {
        return count($this->urlStats->getValues());
    }

    public function getStatsArray(): array
    {
        return array_map(function ($stat) {
            return [
                'deviceType' => $stat->getDeviceType(),
                'date' => date('m/d/Y H:i:s', $stat->getAccessTimestamp())
            ];
        }, $this->urlStats->getValues());
    }

    public function toJson(): array
    {
        return [
            'realUrl' => $this->realUrl,
            'shortUrl' => $this->shortUrl,
            'urlStats' => $this->getStatsArray()
        ];
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
