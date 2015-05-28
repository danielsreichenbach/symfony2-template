<?php

require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

/**
 * Handles the application cache. By default we use the standard settings.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class AppCache extends HttpCache
{
}
