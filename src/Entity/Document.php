<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @Vich\Uploadable
 */
class Document
{
    use TimestampableEntity;

    const IMAGEKIT_ALIAS_SMALL = 'tr:n-small_thumb';
    const IMAGEKIT_ALIAS_MEDIUM = 'tr:n-medium_thumb';
    const IMAGEKIT_ALIAS_LARGE = 'tr:n-large_thumb';
    const IMAGEKIT_ALIAS_MACRO = 'tr:n-media_library_thumbnail';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"artwork:read", "artwork:write"})
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="document_image", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid JPG"
     * )
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"artwork:read", "artwork:write"})
     *
     * @var string|null
     */
    private $imageURI;

    /**
     * @ORM\Column(type="json", nullable=true)
     *
     * @Groups({"artwork:write"})
     *
     * @var string|null
     */
    private $imageKitData;

    /**
     * @ORM\ManyToOne(targetEntity="Artwork", inversedBy="documents")
     */
    private $artwork;

    /**
     * @var string
     */
    private $imageURISmall;

    /**
     * @var string
     */
    private $imageURIMedium;

    /**
     * @var string
     */
    private $imageURILarge;

    /**
     * Document constructor.
     */
    public function __construct()
    {
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
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     *
     * @return Document
     */
    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     *
     * @return Document
     */
    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArtwork()
    {
        return $this->artwork;
    }

    /**
     * @param mixed $artwork
     *
     * @return Document
     */
    public function setArtwork($artwork)
    {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * @param string|null $imageURI
     *
     * @return Document
     */
    public function setImageURI(?string $imageURI): self
    {
        $this->imageURI = $imageURI;

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"artwork:read"})
     */
    public function getImageURI(string $alias = null): ?string
    {
        if ($this->imageURI) {
            return $this->imageURI;
        }

        $imageURI = null;
        try {
            $data = $this->getImageKitData();

            if ($alias && isset($data['thumbnailUrl'])) {
                $imageURI = str_replace(self::IMAGEKIT_ALIAS_MACRO, $alias, $data['thumbnailUrl']);
            } elseif (isset($data['url'])) {
                $imageURI = $data['url'];
            }
        } catch (\Exception $e) {
        }

        return $imageURI;
    }

    /**
     * @return string|null
     * @Groups({"artwork:read"})
     */
    public function getImageURIMedium(): ?string
    {
        return $this->imageURIMedium ?? $this->getImageURI(self::IMAGEKIT_ALIAS_MEDIUM);
    }

    /**
     * @return string|null
     * @Groups({"artwork:read"})
     */
    public function getImageURILarge(): ?string
    {
        return $this->imageURILarge ?? $this->getImageURI(self::IMAGEKIT_ALIAS_LARGE);
    }

    /**
     * @return string|null
     * @Groups({"artwork:read"})
     */
    public function getImageURISmall(): ?string
    {
        return $this->imageURISmall ?? $this->getImageURI(self::IMAGEKIT_ALIAS_SMALL);
    }

    /**
     * @param string $imageURISmall
     *
     * @return Document
     */
    public function setImageURISmall(string $imageURISmall): self
    {
        $this->imageURISmall = $imageURISmall;

        return $this;
    }

    /**
     * @param string $imageURIMedium
     *
     * @return Document
     */
    public function setImageURIMedium(string $imageURIMedium): self
    {
        $this->imageURIMedium = $imageURIMedium;

        return $this;
    }

    /**
     * @param string $imageURILarge
     *
     * @return Document
     */
    public function setImageURILarge(string $imageURILarge): self
    {
        $this->imageURILarge = $imageURILarge;

        return $this;
    }

    /**
     * @return array|null
     * @Groups({"artwork:read"})
     */
    public function getImageKitData(): ?array
    {
        return json_decode($this->imageKitData, true);
    }

    /**
     * @param string|null $imageKitData
     *
     * @return Document
     */
    public function setImageKitData(?string $imageKitData): self
    {
        $this->imageKitData = $imageKitData;

        return $this;
    }
}
