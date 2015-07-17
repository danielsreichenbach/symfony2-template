<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Verifies functionality of the User controller
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Verify that the default page is accessible
     */
    public function testIndex()
    {
        $client = static::createClient();

        /**
         * @var \AppBundle\Entity\User
         */
        $user = $client
            ->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository('AppBundle:User')
            ->findFirst();

        $crawler = $client->request('GET', '/@'.$user->getUsername());

        $this->assertTrue($client->getResponse()->isSuccessful(), 'The response was not successful.');
        $this->assertTrue($crawler->filter('html:contains("'.$user->getUsername().'")')->count() > 0);
    }
}
