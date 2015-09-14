<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Command;

use IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineDocumentCommand;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateDoctrineDocumentCommandTest.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class GenerateDoctrineDocumentCommandTest extends GenerateCommandTest
{
    /**
     * @dataProvider getInteractiveCommandData
     */
    public function testInteractiveCommand($options, $input, $expected)
    {
        list($document, $fields) = $expected;

        $generator = $this->getGenerator();

        $tester = new CommandTester($this->getCommand($generator->reveal(), $input));
        $tester->execute($options);

        $generator
            ->generate($this->getBundle(), $document, $fields, false)
            ->shouldHaveBeenCalledTimes(1);
    }

    public function getInteractiveCommandData()
    {
        return array(
            array(array(), "AcmeBlogBundle:Blog/Post\n", array('Blog\\Post', array())),
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), '', array('Blog\\Post', array())),
            array(array(), "AcmeBlogBundle:Blog/Post\n\n", array('Blog\\Post', array())),
            array(array(),
                "AcmeBlogBundle:Blog/Post\ntitle\n\ndescription\nstring\n\n",
                array('Blog\\Post', array(
                    array('fieldName' => 'title', 'type' => 'string'),
                    array('fieldName' => 'description', 'type' => 'string'),
                )), ),
        );
    }

    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($document, $fields) = $expected;

        $generator = $this->getGenerator();

        $tester = new CommandTester($this->getCommand($generator->reveal(), ''));
        $tester->execute($options, array('interactive' => false));

        $generator
            ->generate($this->getBundle(), $document, $fields, false)
            ->shouldHaveBeenCalledTimes(1);
    }

    public function getNonInteractiveCommandData()
    {
        return array(
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), array('Blog\\Post', array())),
            array(array('--document' => 'AcmeBlogBundle:Blog/Post',
                        '--fields' => 'title:string description:string', ), array('Blog\\Post', array(
                array('fieldName' => 'title', 'type' => 'string'),
                array('fieldName' => 'description', 'type' => 'string'),
            ))),
        );
    }

    protected function getCommand($generator, $input)
    {
        $command = new GenerateDoctrineDocumentCommand();
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setGenerator($generator);

        return $command;
    }

    protected function getGenerator()
    {
        return $this->prophesize('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator');
    }
}
