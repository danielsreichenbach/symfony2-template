# Symfony2 LTS development template

Holy cow, what is this? Well, simply said: the default Symfony2 project
created by Composer or the Symfony2 installer is lacking in several
areas. It is nice if you want to build a simple application for learning
Symfony2 but is by far not what you will require for a real world app.

So, this is going to be it:

* a sane default configuration,
* a testing setup managed via Composer which includes both unit and spec
  driven tools,
* interface testing tools included because who builds an app without an
  interface?
* asset management handed over to Node.js teamed up with Grunt because
  usually there will be blood, sweat and tears shed to get this piped
  into Assetic. Not here, Assetic is gone.
* application deployment support based on Capistrano. This is a hard one
  as usually I would not recommend git based deployment but archive
  deployments and freelance projects are rather rare, right?

And since this will not cut it for your common project, we added a bit
of spice on top of it:

* updating PHP 5.5 apps usually requires wiping the OPcache these days.
  We include helper commands to just do that during deployment.
* a Capistrano 3 deployment recipe which includes everything from setup
  to regular deployments to keeping your target environments clean.
* support for Node.js and Ruby Gem bundles on target hosts.
