<?php

namespace TinectClearCaches\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearCachesCommand extends ShopwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tinect:clear:caches')
            ->setDescription('Clears all caches')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputIsVerbose = $output->isVerbose();
        $io = new SymfonyStyle($input, $output);
        $timeStart = microtime(true);

        $cache = $this->container->get('shopware.cache_manager');

        $io->comment('Clearing the <info>CoreCache</info>');
        $cache->getCoreCache()->clean(); //= Shopware Configuration

        $io->comment('Clearing the <info>TemplateCache</info>');
        $cache->clearTemplateCache();

        $io->comment('Clearing the <info>ProxyCache</info>');
        $cache->clearProxyCache();

        $io->comment('Clearing the <info>OpCache</info>');
        $cache->clearOpCache();

        $io->comment('Clearing the <info>HttpCache</info>');
        $cache->clearHttpCache();

        if ($outputIsVerbose) {
            $io->comment('Removing old cache directory...');
        }

        if ($outputIsVerbose) {
            $io->comment('Finished');
        }

        $timeEnd = round(microtime(true) - $timeStart, 2);
        $io->success(sprintf('Caches were successfully cleared after %s seconds.', $timeEnd));
    }
}
