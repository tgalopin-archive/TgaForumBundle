<?php

namespace Tga\ForumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tgaforum:install')
            ->setDescription('Install Vanilla in the public directory')
            ->addArgument('dirname', InputArgument::OPTIONAL, 'Name of the directory to create (default: forum)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirname = $input->getArgument('dirname');

        if (! $dirname) {
            $dirname = 'forum';
        }

        $filesystem = $this->getContainer()->get('filesystem');

        $originDir = __DIR__ . '/../Resources/vanilla';
        $targetDir = dirname($this->getContainer()->get('kernel')->getRootDir()) . '/web/' . $dirname;

        if (file_exists($targetDir)) {
            throw new \RuntimeException(sprintf(
                'The directory "%s" already exists', $targetDir
            ));
        }

        $filesystem->mkdir($targetDir);
        $filesystem->mirror($originDir, $targetDir, Finder::create()->ignoreDotFiles(false)->in($originDir));

        $output->writeln(sprintf("\nVanilla has been installed in <comment>%s</comment>.", $targetDir));
        $output->writeln("Configure TgaForumBundle :\n");
        $output->writeln("tga_forum:\n    vanilla_path: \"%kernel.root_dir%/../web/". $dirname ."\"\n");
    }
}
