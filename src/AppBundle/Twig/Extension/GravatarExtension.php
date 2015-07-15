<?php

namespace AppBundle\Twig\Extension;

/**
 * Turns Email addresses into Gravatar image URLs
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class GravatarExtension extends \Twig_Extension
{
    /**
     * Returns the list of filters provided by the extension
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('gravatar', array($this, 'gravatarFilter')),
        );
    }

    /**
     * Retrieves a Gravatar via standard URL
     *
     * @param string  $email
     * @param integer $size
     * @param string  $default
     *
     * @return string
     */
    public function gravatarFilter($email, $size = null, $default = null)
    {
        $defaults = array(
            '404',
            'mm',
            'identicon',
            'monsterid',
            'wavatar',
            'retro',
            'blank',
        );

        $hash = md5($email);
        $url = '//www.gravatar.com/avatar/'.$hash;

        // Size
        if (!is_null($size)) {
            $url .= "?s=$size";
        }

        // Default
        if (!is_null($default)) {
            $url .= is_null($size) ? '?' : '&';
            $url .= 'd='.(in_array($default, $defaults) ? $default : urlencode($default));
        }

        return $url;
    }

    /**
     * Returns the name of the Twig extension
     *
     * @return string
     */
    public function getName()
    {
        return 'gravatar_extension';
    }
}
