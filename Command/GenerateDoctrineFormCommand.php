<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;

/**
 * Generates a form type class for a given Doctrine document.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 */
class GenerateDoctrineFormCommand extends GenerateDoctrineCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
            new InputArgument('document', InputArgument::REQUIRED, 'The document class name to initialize (shortcut notation)'),
        ))
            ->setDescription('Generates a form type class based on a Doctrine document')
            ->setHelp(<<<EOT
The <info>doctrine:generate:mongodb:form</info> command generates a form class based on a Doctrine document.

<info>php app/console doctrine:generate:mongodb:form AcmeBlogBundle:Post</info>
EOT
        )
            ->setName('doctrine:mongodb:generate:form')
            ->setAliases(array('generate:doctrine:mongodb:form'));
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $document = Validators::validateDocumentName($input->getArgument('document'));
        list($bundle, $document) = $this->parseShortcutNotation($document);

        /* @var $bundle \Symfony\Component\HttpKernel\Bundle\BundleInterface */
        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        $documentClass = $bundle->getNamespace().'\\Document\\'.$document;

        $metadata = $this->getDocumentMetadata($documentClass);

        $generator = new DoctrineFormGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/skeleton/form');
        $generator->generate($bundle, $document, $metadata);

        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }
}
