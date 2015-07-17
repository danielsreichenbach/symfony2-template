<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Verifies functionality of the default app controller
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Verify that the default page is accessible
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'The response was not successful.');
        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}
