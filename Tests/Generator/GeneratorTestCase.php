<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class GeneratorTest.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
abstract class GeneratorTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $tmpDir;

    /**
     * @var string
     */
    protected $documentName;

    /**
     * @var ClassMetadataInfo
     */
    protected $metadata;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem();

        $this->documentName = ucfirst($this->getName());
        $this->tmpDir = sys_get_temp_dir().'/ismaambrosi';

        $this->metadata = new ClassMetadataInfo($this->documentName);
        $this->metadata->mapField(array(
            'name' => 'id',
            'id' => true,
            'strategy' => 'auto',
        ));

        $this->metadata->mapField(array(
            'fieldName' => 'name',
            'type' => 'string',
        ));

        $this->metadata->mapField(array(
            'fieldName' => 'description',
            'type' => 'string',
        ));
    }

    protected function tearDown()
    {
        $this->getFilesystem()->remove($this->tmpDir);
        parent::tearDown();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getTestBundle()
    {
        $bundle = $this->prophesize('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->getPath()->willReturn($this->tmpDir.'/Foo/BarBundle');
        $bundle->getNamespace()->willReturn('Foo\BarBundle');
        $bundle->getName()->willReturn('FooBarBundle');

        return $bundle->reveal();
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->filesystem;
    }
}
