<?php

namespace App\Service;

use App\Entity\User;
use App\Model\BadgesUser;
use App\Repository\ArtworkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BadgesGenerator
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ArtworkRepository
     */
    protected $artworkRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var BadgesUser
     */
    protected $badgeUser;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var User
     */
    protected $user;

    /**
     * BadgesGenerator constructor.
     *
     * @param UserRepository         $userRepository
     * @param ArtworkRepository      $artworkRepository
     * @param EntityManagerInterface $manager
     * @param LoggerInterface        $logger
     * @param array                  $parameters
     */
    public function __construct(UserRepository $userRepository, ArtworkRepository $artworkRepository,
                                EntityManagerInterface $manager, LoggerInterface $logger, array $parameters)
    {
        $this->userRepository = $userRepository;
        $this->artworkRepository = $artworkRepository;
        $this->manager = $manager;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    /**
     * Generate badges for all users.
     */
    public function badgesGenerator()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $allUsers = $this->userRepository->findAll();
        foreach ($allUsers as $user) {
            try {
                echo 'userid : '.$user->getId();
                $this->user = $user;
                $this->badgeUser = new BadgesUser();

                $this->generateArtworkBadge();
                $this->generateArtistBadge();
                $this->generateCityBadge();
                $this->generateCountryBadge();
                $this->generateInstaBadge();
                $this->generateProfilHunterArtworkBadge();
                $this->generateProfilHunterBadge();
                $this->generateSameArtistBadge();
                $this->generateSameCityBadge();
                $this->generateSameCountryBadge();

                // TODO
                // compare old badges to send notification if new ones
                $this->newBadgesNotification();

                $this->user->setBadges($serializer->serialize($this->badgeUser, 'json'));
                $this->manager->persist($this->user);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
        $this->manager->flush();
    }

    private function newBadgesNotification()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        /**
         * @var BadgesUser $userBadges
         */
        $userBadges = $serializer->deserialize($this->user->getBadges(), BadgesUser::class, 'json');
        echo 'level depuis la bdd : '.$userBadges->getArtworkLevel();
        echo 'level depuis le calcul : '.$this->badgeUser->getArtworkLevel();
    }

    private function generateArtworkBadge()
    {
        //Artworks submitted : 1 / 20 / 200 / 750 / 2000 (special top 10 user ?)
        $artworksNb = count($this->artworkRepository->getArtworksByUser($this->user));
        foreach ($this->parameters['artwork'] as $parameter => $value) {
            if ($value < $artworksNb) {
                $this->badgeUser->setArtworkLevel($parameter);
                break;
            }
        }
    }

    /**
     * TODO.
     */
    private function generateArtistBadge()
    {
        //Artists submitted : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     */
    private function generateCityBadge()
    {
        //Total cities : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     */
    private function generateCountryBadge()
    {
        //Total countries : 1 / 5 / 10 / 20 / 50
    }

    /**
     * TODO.
     */
    private function generateSameArtistBadge()
    {
        //X of same artist : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     */
    private function generateSameCityBadge()
    {
        //X from same city : 1 / 10 / 50 / 100 / 500 (top 3 user)
    }

    /**
     * TODO.
     */
    private function generateSameCountryBadge()
    {
        //X from same country : 1 / 10 / 100 / 500 / 1000 (top 3 user)
    }

    /**
     * TODO.
     */
    private function generateProfilHunterBadge()
    {
        //X views profil hunter : 10 / 50 / 100 / 1000 / 5000
    }

    /**
     * TODO.
     */
    private function generateProfilHunterArtworkBadge()
    {
        //Total artworks views : 100 / 500 / 2000 / 5000 / 10000
    }

    /**
     * TODO.
     */
    private function generateInstaBadge()
    {
        //Instagram photo selected : 1 / 5 / 10 / 50 / 100
    }
}
