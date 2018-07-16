<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use App\Service\FileUploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;
    private $fileUploader;
    private $fixtureImageDirectory;

    /**
     * AdFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param FileUploader $fileUploader
     * @param $fixtureImageDirectory
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader,$fixtureImageDirectory)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploader = $fileUploader;
        $this->fixtureImageDirectory = $fixtureImageDirectory;
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
        [$categoryName, $username, $title, $adContent, $price, $city, $postalCode, $adress, $dateAdded, $files]) {
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
            if (!empty($files)) {
                /** @var File|UploadedFile $file */
                foreach ($files as $file) {
                    $fileName = $this->fileUploader->upload($file);
                    $imagesToAdd = new Image();
                    $imagesToAdd->setName($fileName);
                    $ad->addImagesLink($imagesToAdd);
                    $manager->persist($imagesToAdd);
                }
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }

    private function getAdData(): array
    {

        copy($this->fixtureImageDirectory . 'foret1.jpg', $this->fixtureImageDirectory . '01-copy.jpg');
        $files[] = new File($this->fixtureImageDirectory.'01-copy.jpg');

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
                $files,
            ]
        ];
    }
}
