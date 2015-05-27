<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides means to invalidate the current OPcache state
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class OpcacheClearCommand extends ContainerAwareCommand
{
    /**
     * Path to the applications public HTML directory
     *
     * @var string
     */
    private $webDirectory;

    /**
     * Host name of the target server
     *
     * @var string
     */
    private $hostName;

    /**
     * IP address of the target server
     *
     * @var string
     */
    private $ipAddress;

    /**
     * Protocol to be used when accessing the target server
     *
     * @var string
     */
    private $protocol;

    /**
     * Secret key required to allow cache clearing
     *
     * @var string
     */
    private $secretKey;

    /**
     * Set up the command and its' parameters
     */
    protected function configure()
    {
        $this
            ->setName('app:cache:opcache:clear')
            ->setDescription('Clear PHP OPcache bytecode cache.')
            ->setHelp($this->getCommandHelp());
    }

    /**
     * This method is executed before the interact() and the execute() methods.
     * It's main purpose is to initialize the variables used in the rest of the
     * command methods.
     * Beware that the input options and arguments are validated after executing
     * the interact() method, so you can't blindly trust their values in this method.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->webDirectory = $this->getContainer()->getParameter('app.maintenance.opcache.web_dir');
        $this->hostName     = $this->getContainer()->getParameter('app.maintenance.opcache.host_name');
        $this->ipAddress    = $this->getContainer()->getParameter('app.maintenance.opcache.host_ip');
        $this->protocol     = $this->getContainer()->getParameter('app.maintenance.opcache.protocol');
        $this->secretKey    = $this->getContainer()->getParameter('app.maintenance.opcache.secret');
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir($this->webDirectory)) {
            throw new \InvalidArgumentException(sprintf('Web dir "%s" does not exist.', $this->webDirectory));
        }

        if (!is_writable($this->webDirectory)) {
            throw new \InvalidArgumentException(sprintf('Web dir "%s" is not writable.', $this->webDirectory));
        }

        $filename     = 'opcache-'.$this->secretKey.'.php';
        $file         = $this->webDirectory.'/'.$filename;
        $templateFile = __DIR__.'/../Resources/templates/opcache.php';

        if (!is_readable($templateFile)) {
            throw new \InvalidArgumentException(sprintf('Template file "%s" is not readable.', $templateFile));
        }

        $template = file_get_contents($templateFile);

        if (false === @file_put_contents($file, $template)) {
            throw new \RuntimeException(sprintf('Unable to write "%s"', $file));
        }

        $targetUrl = sprintf('%s://%s/%s', $this->protocol, $this->ipAddress, $filename);

        $curlHandle = curl_init();

        curl_setopt_array(
            $curlHandle,
            array(
                CURLOPT_URL            => $targetUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FAILONERROR    => true,
                CURLOPT_HTTPHEADER     => [sprintf('Host: %s', $this->hostName)],
                CURLOPT_HEADER         => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            )
        );

        $result = curl_exec($curlHandle);

        if (curl_errno($curlHandle)) {
            $error = curl_error($curlHandle);
            curl_close($curlHandle);
            unlink($file);
            throw new \RuntimeException(sprintf('Curl error reading "%s": %s', $targetUrl, $error));
        }

        curl_close($curlHandle);

        $result = json_decode($result, true);
        unlink($file);

        if (!$result) {
            throw new \RuntimeException(sprintf('The response did not return valid JSON: %s', $result));
        }

        if ($result['success']) {
            $output->writeln('<info>OPcache cache was cleared.</info>');
        } else {
            throw new \RuntimeException('Failed to clear OPcache cache.');
        }
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     *
     * @return string
     */
    private function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command will clear the PHP OPcache bytecode cache

  <info>php %command.full_name%</info>
HELP;
    }
}
