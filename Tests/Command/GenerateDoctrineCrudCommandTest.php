<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Command;

use IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateDoctrineCrudCommandTest
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class GenerateDoctrineCrudCommandTest extends GenerateCommandTest
{
    /**
     * @dataProvider getInteractiveCommandData
     */
    public function testInteractiveCommand($options, $input, $expected)
    {
        list($document, $format, $prefix, $withWrite) = $expected;

        /** @var $generator \PHPUnit_Framework_MockObject_MockObject */
        $generator = $this->getGenerator();
        $generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->getBundle(), $document, $this->getDoctrineMetadata(), $format, $prefix, $withWrite);

        /** @var $generator \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\Generator */

        $tester = new CommandTester($this->getCommand($generator, $input));
        $tester->execute($options);
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
            array(array('--document'        => 'AcmeBlogBundle:Blog/Post',
                        '--format'          => 'yml',
                        '--route-prefix'    => 'foo',
                        '--with-write'      => true), '', array('Blog\\Post', 'yml', 'foo', true)),
        );
    }

    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($document, $format, $prefix, $withWrite) = $expected;

        /** @var $generator \PHPUnit_Framework_MockObject_MockObject */
        $generator = $this->getGenerator();
        $generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->getBundle(), $document, $this->getDoctrineMetadata(), $format, $prefix, $withWrite);

        $tester = new CommandTester($this->getCommand($generator, ''));
        $tester->execute($options, array('interactive' => false));
    }

    /**
     * @return array
     */
    public function getNonInteractiveCommandData()
    {
        return array(
            array(array('--document' => 'AcmeBlogBundle:Blog/Post'), array('Blog\\Post', 'annotation', 'blog_post', false)),
            array(array(
                '--document'        => 'AcmeBlogBundle:Blog/Post',
                '--format'          => 'yml',
                '--route-prefix'    => 'foo',
                '--with-write'      => true
            ), array('Blog\\Post', 'yml', 'foo', true)),
        );
    }

    /**
     * @param \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator $generator
     * @param string                                                              $input
     *
     * @return \IsmaAmbrosi\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand
     */
    protected function getCommand($generator, $input)
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

        /** @var $command GenerateDoctrineCrudCommand */
        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setGenerator($generator);
        $command->setFormGenerator($this->getFormGenerator());

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
     * @return \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator
     */
    protected function getGenerator()
    {
        // get a noop generator
        return $this
            ->getMockBuilder('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generate'))
            ->getMock();
    }

    /**
     * @return \IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator
     */
    protected function getFormGenerator()
    {
        return $this
            ->getMockBuilder('IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generate'))
            ->getMock();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer()
    {
        $container = parent::getContainer();

        $dm = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $dm
            ->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue(new \Doctrine\ODM\MongoDB\Configuration()));

        $container->set('doctrine.odm.mongodb.document_manager', $dm);

        return $container;
    }
}
