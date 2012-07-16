<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Command;

use IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineDocumentCommand;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateDoctrineDocumentCommandTest
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
        $generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->getBundle(), $document, $fields);

        $tester = new CommandTester($this->getCommand($generator, $input));
        $tester->execute($options);
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
                ))),
        );
    }

    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($document, $fields) = $expected;

        $generator = $this->getGenerator();
        $generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->getBundle(), $document, $fields);
        $generator
            ->expects($this->any())
            ->method('isReservedKeyword')
            ->will($this->returnValue(false));

        $tester = new CommandTester($this->getCommand($generator, ''));
        $tester->execute($options, array('interactive' => false));
    }

    public function getNonInteractiveCommandData()
    {
        return array(
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), array('Blog\\Post', array())),
            array(array('--document' => 'AcmeBlogBundle:Blog/Post',
                        '--fields' => 'title:string description:string'), array('Blog\\Post', array(
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
        // get a noop generator
        return $this
            ->getMockBuilder('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generate', 'isReservedKeyword'))
            ->getMock();
    }
}
