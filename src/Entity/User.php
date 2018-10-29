<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="contributor")
     */
    private $artworks;

    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     * @Assert\File(
     *     maxSize = "1M",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid JPG"
     * )
     *
     * @var File
     */
    private $avatarFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $avatarName;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->artworks = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return File
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File $avatarFile
     *
     * @return User
     */
    public function setAvatarFile(File $avatarFile): self
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    /**
     * @param string $avatarName
     *
     * @return User
     */
    public function setAvatarName(string $avatarName): self
    {
        $this->avatarName = $avatarName;

        return $this;
    }
}
