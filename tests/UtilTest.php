<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilTest extends WebTestCase
{
    public function testSomething()
    {
        $this->assertTrue(true);
    }

    public function testCheckPassword(){
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/register'
        );

        $form = $crawler->selectButton('Save')->form();

        $form['user[email]'] = 'toto@email.com';
        $form['user[username]'] = 'usernametest';
        $form['user[firstname]'] = 'John';
        $form['user[lastname]'] = 'Doe';
        $form['user[password][first]'] = 'pass1';
        $form['user[password][second]'] = 'pass2';

        $crawler = $client->submit($form);

//        echo $client->getResponse()->getContent();


        $this->assertEquals(1,
            $crawler->filter('.form-error-message:contains("Les mots de passe ne correspondent pas.")')->count()
        );
    }
}
