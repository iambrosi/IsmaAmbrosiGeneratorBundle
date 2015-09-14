<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator;
use Prophecy\Argument;

class DoctrineDocumentGeneratorTest extends GeneratorTestCase
{
    public function testGenerateDocumentWithoutRepository()
    {
        $this->generate(false);

        $this->assertFilesExists(array('Document/Foo.php'));

        $this->assertAttributesAndMethodsExists(array(
            '@ODM\Field(name="bar"',
            '@ODM\Field(name="baz"',
        ));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Document "Foo\BarBundle\Document\Foo" already exists.
     */
    public function testDisallowGeneratingExistingDocuments()
    {
        $this->generate(false);
        $this->generate(false);
    }

    public function testGenerateDocumentWithRepository()
    {
        $this->generate(true);

        $this->assertFilesExists(array(
            'Document/Foo.php',
            'Document/FooRepository.php',
        ));

        $this->assertAttributesAndMethodsExists(array(
            '@ODM\Field(name="bar"',
            '@ODM\Field(name="baz"',
        ));
    }

    public function getManager()
    {
        $manager = $this->prophesize('Doctrine\ODM\MongoDB\DocumentManager');

        $manager->getConfiguration()
            ->willReturn($this->getConfiguration());

        return $manager->reveal();
    }

    public function getConfiguration()
    {
        $config = $this->prophesize('Doctrine\ODM\MongoDB\Configuration');

        $config->addDocumentNamespace(Argument::type('string'), Argument::type('string'))
            ->will(function (array $args) use ($config) {
                $config
                    ->getDocumentNamespace($args[0])
                    ->willReturn($args[1]);
            });

        return $config->reveal();
    }

    protected function assertFilesExists(array $files)
    {
        foreach ($files as $file) {
            $filePath = $this->getTestBundle()->getPath().'/'.$file;
            $this->assertFileExists($filePath, sprintf('%s has been generated', $file));
        }
    }

    protected function assertAttributesAndMethodsExists(
        array $otherStrings = array()
    ) {
        $content = file_get_contents($this->getTestBundle()
                ->getPath().'/Document/Foo.php');

        $strings = array(
            'namespace Foo\\BarBundle\\Document',
            'class Foo',
            'protected $id',
            'protected $bar',
            'protected $baz',
            'public function getId',
            'public function getBar',
            'public function getBaz',
            'public function setBar',
            'public function setBaz',
        );

        $strings = array_merge($strings, $otherStrings);

        foreach ($strings as $string) {
            $this->assertContains($string, $content);
        }
    }

    protected function generate($with_repository = false)
    {
        $this->getGenerator()
            ->generate($this->getTestBundle(), 'Foo', $this->getFields(), $with_repository);
    }

    protected function getGenerator()
    {
        $generator = new DoctrineDocumentGenerator($this->filesystem, $this->getManager());
        $generator->setSkeletonDirs(__DIR__.'/../../Resources/skeleton');

        return $generator;
    }

    protected function getFields()
    {
        return array(
            array('fieldName' => 'bar', 'type' => 'string', 'length' => 255),
            array('fieldName' => 'baz', 'type' => 'integer', 'length' => 11),
        );
    }
}
