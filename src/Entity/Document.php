<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="document_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\ManyToOne(targetEntity="Artwork", inversedBy="documents")
     */
    private $artwork;

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
    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return Document
     */
    public function setImageFile(File $imageFile): Document
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     * @return Document
     */
    public function setImageName(string $imageName): Document
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
}
