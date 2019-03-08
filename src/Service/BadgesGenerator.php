<?php

namespace App\Service;

use App\Model\BadgesUser;
use App\Repository\ArtworkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * BadgesGenerator constructor.
     * @param UserRepository $userRepository
     * @param ArtworkRepository $artworkRepository
     * @param EntityManagerInterface $manager
     * @param array $parameters
     */
    public function __construct(UserRepository $userRepository, ArtworkRepository $artworkRepository,
                                EntityManagerInterface $manager, array $parameters)
    {
        $this->userRepository = $userRepository;
        $this->artworkRepository = $artworkRepository;
        $this->manager = $manager;
        $this->parameters = $parameters;
    }

    /**
     * Generate badges for all users
     */
    public function badgesGenerator(array $badgesParameters)
    {
        $allUsers = $this->userRepository->findAll();
        foreach ($allUsers as $user) {
            $bagdeUser = new BadgesUser();

            //Artworks submitted : 1 / 20 / 200 / 750 / 2000 (special top 10 user ?)
            $this->artworkRepository->getArtworksByUser($user);

            var_dump($this->parameters);

            //Artists submitted : 1 / 5 / 20 / 50 / 100
            //Total cities : 1 / 5 / 20 / 50 / 100
            //Total countries : 1 / 5 / 10 / 20 / 50
            //X of same artist : 1 / 5 / 20 / 50 / 100
            //X from same city : 1 / 10 / 50 / 100 / 500 (top 3 user)
            //X from same country : 1 / 10 / 100 / 500 / 1000 (top 3 user)
            //X views profil hunter : 10 / 50 / 100 / 1000 / 5000
            //Total artworks views : 100 / 500 / 2000 / 5000 / 10000
            //Instagram photo selected : 1 / 5 / 10 / 50 / 100
            $user->setBadges($bagdeUser);
            $this->manager->persist($user);
        }
        $this->manager->flush();

    }
}
