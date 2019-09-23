<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArtworkRepository")
 */
class Artwork
{
    use TimestampableEntity;

    const TYPE_GRAFFITI = 'graffiti';
    const TYPE_STICKING = 'sticking';
    const TYPE_MOSAIC = 'mosaic';
    const TYPE_YARN_BOMBING = 'yarn_bombing';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Poi", inversedBy="artworks", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $poi;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="artwork", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @Assert\Count(min="1")
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="artworks")
     * @Assert\Valid()
     */
    private $author;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="artworks")
     * @Assert\Valid()
     */
    private $contributor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instaLink;

    /**
     * Artwork constructor.
     */
    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->author = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Artwork
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     *
     * @return Artwork
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    /**
     * @param \DateTimeInterface $endedAt
     *
     * @return Artwork
     */
    public function setEndedAt(\DateTimeInterface $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array|null $tags
     *
     * @return Artwork
     */
    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Artwork
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoi()
    {
        return $this->poi;
    }

    /**
     * @param mixed $poi
     *
     * @return Artwork
     */
    public function setPoi($poi)
    {
        $this->poi = $poi;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document $document
     */
    public function addDocument(Document $document)
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setArtwork($this);
        }
    }

    /**
     * @param Document $document
     */
    public function removeDocument(Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     *
     * @return Artwork
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->id.' - '.$this->title.' - '.$this->type;
    }

    /**
     * @return User
     */
    public function getContributor(): ?User
    {
        return $this->contributor;
    }

    /**
     * @param User $contributor
     *
     * @return Artwork
     */
    public function setContributor(?User $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstaLink()
    {
        return $this->instaLink;
    }

    /**
     * @param mixed $instaLink
     *
     * @return Artwork
     */
    public function setInstaLink($instaLink)
    {
        $this->instaLink = $instaLink;

        return $this;
    }
}
