<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            UserFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getAdData() as
        [$categoryName, $username, $title, $adContent, $price, $city, $postalCode, $adress, $dateAdded]) {
            $ad = new Ad();
            $category = $manager->getRepository(Category::class)->findOneByName($categoryName);
            $ad->setCategory($category);
            $ad->setTitle($title);
            $user = $manager->getRepository(User::class)->findOneByUsername($username);
            $ad->setUser($user);
            $ad->setAdContent($adContent);
            $ad->setPrice($price);
            $ad->setCity($city);
            $ad->setPostalCode($postalCode);
            $ad->setAdress($adress);
            $ad->setDateAdded($dateAdded);
            $manager->persist($ad);
        }

        $manager->flush();
    }

    private function getAdData(): array
    {

        return [
            //[$categoryName,$username,$title,$adContent,$price,$city,$postalCode,$adress,$dateAdded]
            [
                'Vehicule',
                'john_user',
                'R19 light',
                'Belle vieille R19 de 95 très légère: pas de siège à l\'arrière,
                 pas de moteur non plus, et pas de plancher. Pour avancer, 
                 utiliser vos pieds comme la famille PierreAFeu ,
                 Ne consomme rien et ne polue pas, idéal pour les verts!! Et en plus vous faite du sport. 
                 Je vous conseille tout de même de mettre des chaussures',
                30000,
                'Paris',
                '75000',
                '5 rue de la déchetterie',
                new \DateTime(),
            ]
        ];
    }
}
