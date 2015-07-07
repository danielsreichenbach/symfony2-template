<?php

namespace AppBundle\Tests\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Verifies that the bundles configuration is properly loaded
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class AppExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if all required bundle parameters are available and set
     *
     * @param string $parameter
     * @param string $expected
     *
     * @dataProvider getDataToTestDefinedParameter
     */
    public function testDefinedParameter($parameter, $expected)
    {
        $container = $this->createContainer($this->getMinimalConfigs());

        $this->assertTrue(
            $container->hasParameter($parameter),
            sprintf('The parameter \'%s\' is defined.', $parameter)
        );
        $this->assertEquals(
            $expected,
            $container->getParameter($parameter),
            sprintf('The parameter \'%s\' has the right value.', $parameter)
        );
    }

    /**
     * Creates a Container with a given configuration
     *
     * @param array $configs
     *
     * @return ContainerBuilder
     */
    public function createContainer(array $configs)
    {
        $container = new ContainerBuilder();
        $extension = new AppExtension('testkernel');
        $extension->load($configs, $container);

        return $container;
    }

    /**
     * Returns a minimal default configuration
     *
     * @return array
     */
    public function getMinimalConfigs()
    {
        return array(
            array(
                'maintenance' => array(
                    'opcache' => array(
                        'secret'    => 'ThisKeyIsNotSecret',
                        'host_ip'   => '127.0.0.1:80',
                        'host_name' => 'acme.com',
                    ),
                ),
                'users' => array(
                    'default_group' => 'Users',
                ),
                'request_context' => array(
                    'host'          => 'acme.com',
                ),
            ),
        );
    }

    /**
     * Tests bundle services
     *
     * @param string $id
     *
     * @dataProvider getDataToTestDefinedService
     */
    public function testDefinedService($id)
    {
        $container = $this->createContainer($this->getMinimalConfigs());

        $this->assertTrue(
            ($container->has($id) || $container->get($id)),
            sprintf('The service (or alias) \'%s\' is defined.', $id)
        );
    }

    /**
     * Provides a list of bundle parameters and settings to test
     *
     * @return array
     */
    public function getDataToTestDefinedParameter()
    {
        return array(
            array(
                'app.maintenance.opcache.secret',
                'ThisKeyIsNotSecret',
            ),
            array(
                'app.maintenance.opcache.host_ip',
                '127.0.0.1:80',
            ),
            array(
                'app.maintenance.opcache.host_name',
                'acme.com',
            ),
            array(
                'app.maintenance.opcache.protocol',
                'http',
            ),
            array(
                'app.maintenance.opcache.web_dir',
                '%kernel.root_dir%/../web',
            ),
            array(
                'app.users.default_group',
                'Users',
            ),
            array(
                'app.request_context.host',
                'acme.com',
            ),
            array(
                'app.request_context.protocol',
                'http',
            ),
        );
    }

    /**
     * Provides a list of bundle services to test
     *
     * @return array
     */
    public function getDataToTestDefinedService()
    {
        return array(
            array('app.listener.user.events'),
            array('app.listener.request.locale'),
            array('app.listener.session.user.locale'),
            array('app.registration.form.type'),
            array('app.invitation.form.type'),
            array('app.invitation.form.data_transformer'),
            array('app.service.user'),
            array('app.service.menu'),
            array('app.service.menu.user'),
        );
    }
}
