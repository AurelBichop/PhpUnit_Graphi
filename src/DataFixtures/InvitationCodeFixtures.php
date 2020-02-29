<?php


namespace App\DataFixtures;


use App\Entity\InvitationCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class InvitationCodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $code = (new InvitationCode())
            ->setCode("54321")
            ->setDescription("Code de test")
            ->setExpireAt(new \DateTime());

        $manager->persist($code);

        $manager->flush();
    }
}