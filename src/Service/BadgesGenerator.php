<?php

namespace App\Service;

use App\Entity\User;
use App\Model\BadgesUser;
use App\Repository\ArtworkRepository;
use App\Repository\AuthorRepository;
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
     * @var AuthorRepository
     */
    protected $authorRepository;

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
     * @var Mailer
     */
    private $mailer;

    /**
     * BadgesGenerator constructor.
     * @param UserRepository $userRepository
     * @param ArtworkRepository $artworkRepository
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $manager
     * @param LoggerInterface $logger
     * @param Mailer $mailer
     * @param array $parameters
     */
    public function __construct(UserRepository $userRepository, ArtworkRepository $artworkRepository, AuthorRepository $authorRepository,
                                EntityManagerInterface $manager, LoggerInterface $logger, Mailer $mailer, array $parameters)
    {
        $this->userRepository = $userRepository;
        $this->artworkRepository = $artworkRepository;
        $this->authorRepository = $authorRepository;
        $this->manager = $manager;
        $this->parameters = $parameters;
        $this->logger = $logger;
        $this->mailer = $mailer;
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
        $newBadges = [];

        /**
         * @var BadgesUser $userBadges
         */
        $userBadges = $serializer->deserialize($this->user->getBadges(), BadgesUser::class, 'json');
        echo 'level depuis la bdd : '.$userBadges->getArtworkLevel();
        echo 'level depuis le calcul : '.$this->badgeUser->getArtworkLevel();

        if ($userBadges->getArtworkLevel() != $this->badgeUser->getArtworkLevel()) $newBadges['artwork'] = $this->badgeUser->getArtworkLevel();
        if ($userBadges->getArtistLevel() != $this->badgeUser->getArtistLevel()) $newBadges['artist'] = $this->badgeUser->getArtistLevel();
        if ($userBadges->getCityLevel() != $this->badgeUser->getCityLevel()) $newBadges['city'] = $this->badgeUser->getCityLevel();
        if ($userBadges->getCountryLevel() != $this->badgeUser->getCountryLevel()) $newBadges['country'] = $this->badgeUser->getCountryLevel();

        if ($newBadges) $this->mailer->sendNewBadgesEmailMessage($this->user, $newBadges);
    }

    private function generateArtworkBadge()
    {
        $artworksNb = count($this->artworkRepository->getArtworksByUser($this->user));
        foreach ($this->parameters['artwork'] as $parameter => $value) {
            if ($value < $artworksNb) {
                $this->badgeUser->setArtworkLevel($parameter);
                break;
            }
        }
    }

    private function generateArtistBadge()
    {
        $artistsNb = count($this->authorRepository->getAuthorsArtworksByUser($this->user));
        foreach ($this->parameters['artist'] as $parameter => $value) {
            if ($value < $artistsNb) {
                $this->badgeUser->setArtistLevel($parameter);
                break;
            }
        }
    }

    private function generateCityBadge()
    {
        $citiesNb = count($this->artworkRepository->getArtworksCitiesByUser($this->user));
        foreach ($this->parameters['city'] as $parameter => $value) {
            if ($value < $citiesNb) {
                $this->badgeUser->setCityLevel($parameter);
                break;
            }
        }
    }

    private function generateCountryBadge()
    {
        $countriesNb = count($this->artworkRepository->getArtworksCountriesByUser($this->user));
        foreach ($this->parameters['country'] as $parameter => $value) {
            if ($value < $countriesNb) {
                $this->badgeUser->setCountryLevel($parameter);
                break;
            }
        }
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
