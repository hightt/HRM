<?php
declare(strict_types = 1);

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create("pl_PL");
        for ($i = 0; $i <= 100; $i++) {
            $user = new User();
            $user
            ->setEmail($generator->email())
            ->setPassword('$2y$13$KvGQiXeV.DEry4ehoDNiU.DwXHElFM5UvcvpnUsqAIaVAYvZKS6DS')
            ->setIsVerified(true)
            ;
            $manager->persist($user);
        }
        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['0'];
    }


}
