<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *       "get"={
 *         "normalization_context"={"groups"={"user:read"}},
 *         "access_control"="is_granted('ROLE_ADMIN') or user == object",
 *       }
 *     }
 * )
 *
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
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(name="facebookAccessToken", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="instagramId", type="string", length=255, nullable=true)
     */
    protected $instagramId;

    /**
     * @ORM\Column(name="instagramAccessToken", type="string", length=255, nullable=true)
     */
    protected $instagramAccessToken;

    /**
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="contributor")
     */
    private $artworks;

    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @Groups("user:read")
     */
    protected $city;

    /**
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     * @Assert\Url
     */
    protected $website;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups("user:read")
     */
    protected $description;

    /**
     * @ORM\Column(name="language", type="text")
     * @Groups("user:read")
     */
    protected $language;

    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     * @Assert\File(
     *     maxSize = "2M",
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
        $this->language = 'en';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     *
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
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

    /**
     * @return mixed
     */
    public function getArtworks()
    {
        return $this->artworks;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstagramId()
    {
        return $this->instagramId;
    }

    /**
     * @param mixed $instagramId
     *
     * @return User
     */
    public function setInstagramId($instagramId)
    {
        $this->instagramId = $instagramId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstagramAccessToken()
    {
        return $this->instagramAccessToken;
    }

    /**
     * @param mixed $instagramAccessToken
     *
     * @return User
     */
    public function setInstagramAccessToken($instagramAccessToken)
    {
        $this->instagramAccessToken = $instagramAccessToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

}
