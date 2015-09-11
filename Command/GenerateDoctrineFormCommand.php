<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDoctrineFormCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $document = Validators::validateDocumentName($input->getArgument('document'));
        list($bundle, $document) = $this->parseShortcutNotation($document);

        /** @var $application \Symfony\Bundle\FrameworkBundle\Console\Application */
        $application = $this->getApplication();

        /* @var $bundle \Symfony\Component\HttpKernel\Bundle\BundleInterface */
        $bundle = $application->getKernel()->getBundle($bundle);

        $documentClass = $bundle->getNamespace().'\\Document\\'.$document;

        $metadata = $this->getDocumentMetadata($documentClass);

        $generator = new DoctrineFormGenerator($this->getSkeletonPath().'/form');
        $generator->generate($bundle, $document, $metadata);

        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }
}
