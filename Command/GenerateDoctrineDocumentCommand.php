<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Doctrine\ODM\MongoDB\Types\Type;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class GenerateDoctrineDocumentCommand extends GenerateDoctrineCommand
{
    /**
     * @var DoctrineDocumentGenerator
     */
    private $generator;

    /**
     * @return DoctrineDocumentGenerator
     */
    public function getGenerator()
    {
        if (null === $this->generator) {
            $this->generator = new DoctrineDocumentGenerator($this->getFilesystem(), $this->getDocumentManager());
        }

        return $this->generator;
    }

    /**
     * @param \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator $generator
     */
    public function setGenerator(DoctrineDocumentGenerator $generator)
    {
        $this->generator = $generator;
    }

    protected function configure()
    {
        $this
            ->setName('doctrine:mongodb:generate:document')
            ->setAliases(array('generate:doctrine:mongodb:document'))
            ->setDescription('Generates a new Doctrine document inside a bundle')
            ->addOption('document', null, InputOption::VALUE_REQUIRED, 'The document class name to initialize (shortcut notation)')
            ->addOption('fields', null, InputOption::VALUE_REQUIRED, 'The fields to create with the new document')
            ->addOption('with-repository', null, InputOption::VALUE_NONE, 'Whether to generate the document repository or not')
            ->setHelp(<<<EOT
The <info>doctrine:mongodb:generate:document</info> task generates a new Doctrine
document inside a bundle:

<info>php app/console doctrine:mongodb:generate:document --document=AcmeBlogBundle:Blog/Post</info>

The above command would initialize a new document in the following document
namespace <info>Acme\BlogBundle\Document\Blog\Post</info>.

You can also optionally specify the fields you want to generate in the new
document:

<info>php app/console doctrine:mongodb:generate:document --document=AcmeBlogBundle:Blog/Post --fields="title:string body:string"</info>

The command can also generate the corresponding document repository class with the
<comment>--with-repository</comment> option:

<info>php app/console doctrine:mongodb:generate:document --document=AcmeBlogBundle:Blog/Post --with-repository</info>

To deactivate the interaction mode, simply use the `--no-interaction` option
without forgetting to pass all needed options:

<info>php app/console doctrine:mongodb:generate:document --document=AcmeBlogBundle:Blog/Post --fields="title:string body:string" --with-repository --no-interaction</info>
EOT
            );
    }

    /**
     * @throws \InvalidArgumentException When the bundle doesn't end with Bundle (Example: "Bundle/MySampleBundle")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getQuestionHelper();

        if ($input->isInteractive()) {
            $confirmationQuestion = new ConfirmationQuestion($dialog->getQuestion('Do you confirm generation', 'yes', '?'), true);
            if (!$dialog->ask($input, $output, $confirmationQuestion)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        $document = Validators::validateDocumentName($input->getOption('document'));
        list($bundle, $document) = $this->parseShortcutNotation($document);
        $fields = $this->parseFields($input->getOption('fields'));

        $dialog->writeSection($output, 'Document generation');

        $bundle = $this->getKernel()->getBundle($bundle);

        $generator = $this->getGenerator();
        $generator->generate($bundle, $document, array_values($fields), $input->getOption('with-repository'));

        $output->writeln('Generating the document code: <info>OK</info>');

        $dialog->writeGeneratorSummary($output, array());

        return 0;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getQuestionHelper();
        $dialog->writeSection($output, 'Welcome to the Doctrine2 document generator');

        // namespace
        $output->writeln(array(
            '',
            'This command helps you generate Doctrine2 documents.',
            '',
            'First, you need to give the document name you want to generate.',
            'You must use the shortcut notation like <comment>AcmeBlogBundle:Post</comment>.',
            '',
        ));

        list($bundle, $document) = $this->askForDocument($input, $output, $dialog);

        // fields
        $input->setOption('fields', $this->addFields($input, $output, $dialog));

        // repository?
        $output->writeln('');
        $repositoryQuestion = new ConfirmationQuestion($dialog->getQuestion('Do you want to generate an empty repository class', $input->getOption('with-repository') ? 'yes' : 'no', '?'), $input->getOption('with-repository'));
        $withRepository = $dialog->ask($input, $output, $repositoryQuestion);
        $input->setOption('with-repository', $withRepository);

        // summary
        $output->writeln(array(
            '',
            $this->getFormatter()->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf('You are going to generate a "<info>%s:%s</info>" Doctrine2 document.', $bundle, $document),
            '',
        ));
    }

    /**
     * @param string $input
     *
     * @return array
     */
    private function parseFields($input)
    {
        if (is_array($input)) {
            return $input;
        }

        $fields = array();
        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);
            $name = $elements[0];
            if (strlen($name)) {
                $type = isset($elements[1]) ? $elements[1] : 'string';
                preg_match_all('/(.*)\((.*)\)/', $type, $matches);
                $type = isset($matches[1][0]) ? $matches[1][0] : $type;

                $fields[$name] = array('fieldName' => $name, 'type' => $type);
            }
        }

        return $fields;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface              $input
     * @param \Symfony\Component\Console\Output\OutputInterface            $output
     * @param \Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper $dialog
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function addFields(InputInterface $input, OutputInterface $output, QuestionHelper $dialog)
    {
        $fields = $this->parseFields($input->getOption('fields'));
        $output->writeln(array(
            '',
            'Instead of starting with a blank document, you can add some fields now.',
            'Note that the primary key will be added automatically (named <comment>id</comment>).',
            '',
        ));
        $output->write('<info>Available types:</info> ');

        $types = array_keys(Type::getTypesMap());
        $count = 20;
        foreach ($types as $i => $type) {
            if ($count > 50) {
                $count = 0;
                $output->writeln('');
            }
            $count += strlen($type);
            $output->write(sprintf('<comment>%s</comment>', $type));
            $output->write(count($types) != $i + 1 ? ', ' : '.');
        }
        $output->writeln('');

        $fieldValidator = function ($type) use ($types) {
            if (!in_array($type, $types)) {
                throw new \InvalidArgumentException(sprintf('Invalid type "%s".', $type));
            }

            return $type;
        };

        while (true) {
            $output->writeln('');

            if (!$name = $this->askForFieldName($input, $output, $dialog, $fields)) {
                break;
            }

            $defaultType = 'string';
            if (substr($name, -3) == '_at') {
                $defaultType = 'timestamp';
            }

            $question = new Question($dialog->getQuestion('Field type', $defaultType), $defaultType);
            $question->setValidator($fieldValidator);
            $type = $dialog->ask($input, $output, $question);

            $fields[$name] = array('fieldName' => $name, 'type' => $type);
        }

        return $fields;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $dialog
     *
     * @return array
     */
    private function askForDocument(InputInterface $input, OutputInterface $output, QuestionHelper $dialog)
    {
        do {
            $retry = true;
            $question = new Question($dialog->getQuestion('The Document shortcut name', $input->getOption('document')), $input->getOption('document'));
            $question->setValidator(array(
                'IsmaAmbrosi\Bundle\GeneratorBundle\Command\Validators',
                'validateDocumentName',
            ), $input->getOption('document'));
            $document = $dialog->ask($input, $output, $question);

            list($bundle, $document) = $this->parseShortcutNotation($document);

            try {
                $b = $this->getKernel()->getBundle($bundle);

                if (!file_exists($b->getPath().'/Document/'.str_replace('\\', '/', $document).'.php')) {
                    $retry = false;
                } else {
                    $output->writeln(sprintf('<bg=red>Document "%s:%s" already exists</>.', $bundle, $document));
                }
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        } while ($retry);
        $input->setOption('document', $bundle.':'.$document);

        return array($bundle, $document);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $dialog
     * @param array           $fields
     *
     * @return string
     */
    private function askForFieldName(InputInterface $input, OutputInterface $output, QuestionHelper $dialog, $fields)
    {
        $question = new Question($dialog->getQuestion('New field name (press <return> to stop adding fields)', null));
        $question->setValidator(function ($name) use ($fields) {
            if (isset($fields[$name]) || 'id' == $name) {
                throw new \InvalidArgumentException(sprintf('Field "%s" is already defined.', $name));
            }

            return $name;
        });

        return $dialog->ask($input, $output, $question);
    }
}
