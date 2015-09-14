<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Command;

use IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateDoctrineCrudCommandTest.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class GenerateDoctrineCrudCommandTest extends GenerateCommandTest
{
    private $generator, $formGenerator, $dm;

    /**
     * @dataProvider getInteractiveCommandData
     */
    public function testInteractiveCommand($options, $input, $expected)
    {
        list($document, $format, $prefix, $withWrite) = $expected;

        $tester = new CommandTester($this->getCommand($input));
        $tester->execute($options);

        $this->generator
            ->generate($this->getBundle(), $document, $this->getDoctrineMetadata(), $format, $prefix, $withWrite)
            ->shouldHaveBeenCalledTimes(1);

        $this->formGenerator
            ->generate($this->getBundle(), $document, $this->getDoctrineMetadata())
            ->shouldHaveBeenCalledTimes($withWrite ? 1 : 0);
    }

    /**
     * @return array
     */
    public function getInteractiveCommandData()
    {
        return array(
            array(array(), "AcmeBlogBundle:Blog/Post\n", array('Blog\\Post', 'annotation', 'blog_post', false)),
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), '', array('Blog\\Post', 'annotation', 'blog_post', false)),
            array(array(), "AcmeBlogBundle:Blog/Post\ny\nyml\nfoobar\n", array('Blog\\Post', 'yml', 'foobar', true)),
            array(array(), "AcmeBlogBundle:Blog/Post\ny\nyml\n/foobar\n", array('Blog\\Post', 'yml', 'foobar', true)),
            array(array('--document' => 'AcmeBlogBundle:Blog/Post',
                        '--format' => 'yml',
                        '--route-prefix' => 'foo',
                        '--with-write' => true, ), '', array('Blog\\Post', 'yml', 'foo', true)),
        );
    }

    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($document, $format, $prefix, $withWrite) = $expected;

        $tester = new CommandTester($this->getCommand(''));
        $tester->execute($options, array('interactive' => false));

        $this->generator
            ->generate($this->getBundle(), $document, $this->getDoctrineMetadata(), $format, $prefix, $withWrite)
            ->shouldHaveBeenCalledTimes(1);

        $this->formGenerator
            ->generate($this->getBundle(), $document, $this->getDoctrineMetadata())
            ->shouldHaveBeenCalledTimes($withWrite ? 1 : 0);
    }

    /**
     * @return array
     */
    public function getNonInteractiveCommandData()
    {
        return array(
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), array('Blog\\Post', 'annotation', 'blog_post', false)),
            array(array(
                '--document' => 'AcmeBlogBundle:Blog/Post',
                '--format' => 'yml',
                '--route-prefix' => 'foo',
                '--with-write' => true,
            ), array('Blog\\Post', 'yml', 'foo', true)),
        );
    }

    /**
     * @param string $input
     *
     * @return GenerateDoctrineCrudCommand
     */
    protected function getCommand($input)
    {
        /** @var $command \PHPUnit_Framework_MockObject_MockObject */
        $command = $this
            ->getMockBuilder('IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand')
            ->setMethods(array('getDocumentMetadata'))
            ->getMock();

        $command
            ->expects($this->any())
            ->method('getDocumentMetadata')
            ->will($this->returnValue($this->getDoctrineMetadata()));

        /* @var $command GenerateDoctrineCrudCommand */
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setGenerator($this->generator->reveal());
        $command->setFormGenerator($this->formGenerator->reveal());

        return $command;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo
     */
    protected function getDoctrineMetadata()
    {
        return $this
            ->getMockBuilder('Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer()
    {
        $container = parent::getContainer();
        $container->set('doctrine.odm.mongodb.document_manager', $this->dm->reveal());

        return $container;
    }

    protected function setUp()
    {
        parent::setUp();

        $this->generator = $this->prophesize('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator');
        $this->formGenerator = $this->prophesize('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator');

        $this->dm = $this->prophesize('Doctrine\ODM\MongoDB\DocumentManager');

        $this->dm
            ->getConfiguration()
            ->willReturn(new \Doctrine\ODM\MongoDB\Configuration());
    }
}
