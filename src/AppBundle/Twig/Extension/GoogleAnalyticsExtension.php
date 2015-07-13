<?php

namespace AppBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Twig extensions to display google analytics tags
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class GoogleAnalyticsExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var boolean
     */
    protected $firstTracker = true;

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('google_analytics_tracker', array($this, 'tracker'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the name of the Twig extension
     *
     * @return string
     */
    public function getName()
    {
        return 'app.twig.extension.google_analytics';
    }

    /**
     * Renders the tracker.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function tracker($name = 'default')
    {
        $key = 'app.google_analytics.'.$name;

        if (!$this->container->hasParameter($key)) {
            return '';
        }

        $tracker         = $this->container->getParameter($key);
        $tracker['name'] = $name;

        $html = $this->container->get('templating')->render(
            'AppBundle:Google:Analytics.html.twig',
            array('tracker' => $tracker, 'loadGa' => $this->firstTracker)
        );

        $this->firstTracker = false;

        return $html;
    }
}
