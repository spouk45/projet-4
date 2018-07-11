<?php
/**
 * Created by PhpStorm.
 * User: wilder10
 * Date: 11/07/18
 * Time: 11:56
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$lastname, $firstname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastName($lastname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$lastname,$firstname, $username, $password, $email, $roles];
            ['Doe', 'Jane', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Doe', 'Tom ', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['Doe', 'John ', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
            ['admin', 'admin ', 'admin', 'kitten', 'admin@symfony.com', ['ROLE_ADMIN']],
        ];
    }


}