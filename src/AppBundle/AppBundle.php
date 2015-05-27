<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This class is the one that transforms the src/AppBundle/ directory into a real
 * Symfony bundle. There are two types of bundles:
 *
 *   * Reusable Bundles: they are meant to be shared between different applications.
 *     A lot of them are even publicly available in sites like packagist.org.
 *     See http://symfony.com/doc/2.7/cookbook/bundles/best_practices.html
 *   * Application bundles: they are never shared, not even with other of your
 *     applications. This allows them to be less strict in some conventions and
 *     their code is usually simpler.
 *     See http://symfony.com/doc/2.7/best_practices/business-logic.html
 *
 * The AppBundle is an application bundle that is already created when you install
 * Symfony. Using AppBundle to start developing your Symfony application is
 * considered a good practice, but you can also split your application code into
 * as many bundles as you want.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class AppBundle extends Bundle
{
}
