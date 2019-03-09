<?php

namespace App\Service;

use App\Model\BadgesUser;
use App\Repository\ArtworkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

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
        $this->bagdeUser = new BadgesUser();
    }

    /**
     * Generate badges for all users.
     */
    public function badgesGenerator()
    {
        $allUsers = $this->userRepository->findAll();
        foreach ($allUsers as $user) {
            try {
                $this->generateArtworkBadge($user);
                $this->generateArtistBadge($user);
                $this->generateCityBadge($user);
                $this->generateCountryBadge($user);
                $this->generateInstaBadge($user);
                $this->generateProfilHunterArtworkBadge($user);
                $this->generateProfilHunterBadge($user);
                $this->generateSameArtistBadge($user);
                $this->generateSameCityBadge($user);
                $this->generateSameCountryBadge($user);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }

            $user->setBadges($this->badgeUser);
            $this->manager->persist($user);
        }
        $this->manager->flush();
    }

    /**
     * @param $user
     */
    private function generateArtworkBadge($user)
    {
        //Artworks submitted : 1 / 20 / 200 / 750 / 2000 (special top 10 user ?)
        $this->artworkRepository->getArtworksByUser($user);
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateArtistBadge($user)
    {
        //Artists submitted : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateCityBadge($user)
    {
        //Total cities : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateCountryBadge($user)
    {
        //Total countries : 1 / 5 / 10 / 20 / 50
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateSameArtistBadge($user)
    {
        //X of same artist : 1 / 5 / 20 / 50 / 100
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateSameCityBadge($user)
    {
        //X from same city : 1 / 10 / 50 / 100 / 500 (top 3 user)
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateSameCountryBadge($user)
    {
        //X from same country : 1 / 10 / 100 / 500 / 1000 (top 3 user)
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateProfilHunterBadge($user)
    {
        //X views profil hunter : 10 / 50 / 100 / 1000 / 5000
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateProfilHunterArtworkBadge($user)
    {
        //Total artworks views : 100 / 500 / 2000 / 5000 / 10000
    }

    /**
     * TODO.
     *
     * @param $user
     */
    private function generateInstaBadge($user)
    {
        //Instagram photo selected : 1 / 5 / 10 / 50 / 100
    }
}
