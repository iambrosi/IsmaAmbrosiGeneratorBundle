<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GenerateDoctrineCrudCommand extends GenerateDoctrineCommand
{

    private $generator;

    private $formGenerator;

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
            new InputOption('document', '', InputOption::VALUE_REQUIRED, 'The document class name to initialize (shortcut notation)'),
            new InputOption('route-prefix', '', InputOption::VALUE_REQUIRED, 'The route prefix'),
            new InputOption('controller-name', '', InputOption::VALUE_REQUIRED, 'The controller name'),
            new InputOption('with-write', '', InputOption::VALUE_NONE, 'Whether or not to generate create, new and delete actions'),
            new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Use the format for configuration files (php, xml, yml, or annotation)', 'annotation'),
        ))
            ->setDescription('Generates a CRUD based on a Doctrine document')
            ->setHelp(<<<EOT
The <info>doctrine:generate:crud</info> command generates a CRUD based on a Doctrine document.

The default command only generates the list and show actions.

<info>php app/console doctrine:generate:crud --document=AcmeBlogBundle:Post --route-prefix=post_admin</info>

Using the --with-write option allows to generate the new, edit and delete actions.

<info>php app/console doctrine:generate:crud --document=AcmeBlogBundle:Post --route-prefix=post_admin --with-write</info>
EOT
        )
            ->setName('doctrine:mongodb:generate:crud')
            ->setAliases(array('generate:doctrine:mongodb:crud'));
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();

        if ($input->isInteractive()) {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm generation', 'yes', '?'), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        $document = Validators::validateDocumentName($input->getOption('document'));
        list($bundle, $document) = $this->parseShortcutNotation($document);

        $controller = Validators::validateControllerName($input->getOption('controller-name'));

        $format    = Validators::validateFormat($input->getOption('format'));
        $prefix    = $this->getRoutePrefix($input, $document);
        $withWrite = $input->getOption('with-write');

        $dialog->writeSection($output, 'CRUD generation');

        $documentClass = $this->getDocumentNamespace($bundle).'\\'.$document;
        $metadata      = $this->getDocumentMetadata($documentClass);
        $bundle        = $this->getBundle($bundle);

        $generator = $this->getGenerator();
        $generator->generate($bundle, $document, $controller, $metadata, $format, $prefix, $withWrite);

        $output->writeln('Generating the CRUD code: <info>OK</info>');

        $errors = array();
        $runner = $dialog->getRunner($output, $errors);

        // form
        if ($withWrite) {
            $this->generateForm($bundle, $document, $metadata);
            $output->writeln('Generating the Form code: <info>OK</info>');
        }

        // routing
        if ('annotation' != $format) {
            call_user_func($runner, $this->updateRouting($dialog, $input, $output, $bundle, $format, $document, $prefix));
        }

        $dialog->writeGeneratorSummary($output, $errors);

        return 0;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the Doctrine2 CRUD generator');

        // namespace
        $output->writeln(array(
            '',
            'This command helps you generate CRUD controllers and templates.',
            '',
            'First, you need to give the document for which you want to generate a CRUD.',
            'You can give a document that does not exist yet and the wizard will help',
            'you defining it.',
            '',
            'You must use the shortcut notation like <comment>AcmeBlogBundle:Post</comment>.',
            '',
        ));

        $document = $dialog->askAndValidate($output, $dialog->getQuestion('The Document shortcut name', $input->getOption('document')), array(
            'IsmaAmbrosi\Bundle\GeneratorBundle\Command\Validators',
            'validateDocumentName'
        ), false, $input->getOption('document'));
        $input->setOption('document', $document);
        list($bundle, $document) = $this->parseShortcutNotation($document);

        // Document exists?
        $documentClass = $this->getDocumentNamespace($bundle).'\\'.$document;

        $output->writeln(array(
            '',
            'Now you can set the name for the controller. By default, the generator creates',
            'the controller name based on the document.',
            '',
            'It is recommended to separate namespaces with a "/" instead of the "\\"',
            ''
        ));

        $controllerName = $input->getOption('controller-name') ? $input->getOption('controller-name') : $document;
        $controllerName = $dialog->askAndValidate($output, $dialog->getQuestion('The controller name', $controllerName), array(
            'IsmaAmbrosi\Bundle\GeneratorBundle\Command\Validators',
            'validateControllerName'
        ), false, $controllerName);
        $input->setOption('controller-name', $controllerName);

        // write?
        $withWrite = $input->getOption('with-write') ? : false;
        $output->writeln(array(
            '',
            'By default, the generator creates two actions: list and show.',
            'You can also ask it to generate "write" actions: new, update, and delete.',
            '',
        ));
        $withWrite = $dialog->askConfirmation($output, $dialog->getQuestion('Do you want to generate the "write" actions', $withWrite ? 'yes' : 'no', '?'), $withWrite);
        $input->setOption('with-write', $withWrite);

        // format
        $format = $input->getOption('format');
        $output->writeln(array(
            '',
            'Determine the format to use for the generated CRUD.',
            '',
        ));
        $format = $dialog->askAndValidate($output, $dialog->getQuestion('Configuration format (yml, xml, php, or annotation)', $format), array(
            'Sensio\Bundle\GeneratorBundle\Command\Validators',
            'validateFormat'
        ), false, $format);
        $input->setOption('format', $format);

        // route prefix
        $prefix = $this->getRoutePrefix($input, $document);
        $output->writeln(array(
            '',
            'Determine the routes prefix (all the routes will be "mounted" under this',
            'prefix: /prefix/, /prefix/new, ...).',
            '',
        ));
        $prefix = $dialog->ask($output, $dialog->getQuestion('Routes prefix', '/'.$prefix), '/'.$prefix);
        $input->setOption('route-prefix', $prefix);

        // summary
        $output->writeln(array(
            '',
            $this->getFormatter()->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a CRUD controller for \"<info>%s:%s</info>\"", $bundle, $document),
            sprintf("using the \"<info>%s</info>\" format.", $format),
            '',
        ));
    }

    /**
     * Tries to generate forms if they don't exist yet and if we need write operations on documents.
     */
    private function generateForm(BundleInterface $bundle, $document, $metadata)
    {
        try {
            $this->getFormGenerator()->generate($bundle, $document, $metadata);
        } catch (\RuntimeException $e) {
            // form already exists
        }
    }

    private function updateRouting(DialogHelper $dialog, InputInterface $input, OutputInterface $output, BundleInterface $bundle, $format, $document, $prefix)
    {
        $auto = true;
        if ($input->isInteractive()) {
            $auto = $dialog->askConfirmation($output, $dialog->getQuestion('Confirm automatic update of the Routing', 'yes', '?'), true);
        }

        $output->write('Importing the CRUD routes: ');
        $this->getFilesystem()->mkdir($bundle->getPath().'/Resources/config/');
        $routing = new RoutingManipulator($bundle->getPath().'/Resources/config/routing.yml');
        $ret     = $auto ? $routing->addResource($bundle->getName(), $format, '/'.$prefix, 'routing/'.strtolower(str_replace('\\', '_', $document))) : false;
        if (!$ret) {
            $help = sprintf("        <comment>resource: \"@%s/Resources/config/routing/%s.%s\"</comment>\n", $bundle->getName(), strtolower(str_replace('\\', '_', $document)), $format);
            $help .= sprintf("        <comment>prefix:   /%s</comment>\n", $prefix);

            return array(
                '- Import the bundle\'s routing resource in the bundle routing file',
                sprintf('  (%s).', $bundle->getPath().'/Resources/config/routing.yml'),
                '',
                sprintf('    <comment>%s:</comment>', $bundle->getName().('' !== $prefix ? '_'.str_replace('/', '_', $prefix) : '')),
                $help,
                '',
            );
        }

        return null;
    }

    protected function getRoutePrefix(InputInterface $input, $document)
    {
        $prefix = $input->getOption('route-prefix') ? : strtolower(str_replace(array('\\', '/'), '_', $document));

        if ($prefix && '/' === $prefix[0]) {
            $prefix = substr($prefix, 1);
        }

        return $prefix;
    }

    /**
     * @return \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator
     */
    protected function getGenerator()
    {
        if (null === $this->generator) {
            $this->generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../Resources/skeleton/crud');
        }

        return $this->generator;
    }

    public function setGenerator(DoctrineCrudGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return DoctrineFormGenerator
     */
    protected function getFormGenerator()
    {
        if (null === $this->formGenerator) {
            $this->formGenerator = new DoctrineFormGenerator($this->getFilesystem(), __DIR__.'/../Resources/skeleton/form');
            ;
        }

        return $this->formGenerator;
    }

    public function setFormGenerator(DoctrineFormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }
}
