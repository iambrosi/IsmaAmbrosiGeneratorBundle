<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Tests\WebTestCase;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Class GeneratorTest
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
abstract class GeneratorTest extends WebTestCase
{

    protected $tmpDir;
    protected $documentName;
    protected $metadata;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->documentName = ucfirst($this->getName());
        $this->tmpDir = sys_get_temp_dir().'/ismaambrosi';
        $this->getFilesystem()->remove($this->tmpDir);

        $this->metadata = new ClassMetadataInfo($this->documentName);
        $this->metadata->mapField(array(
            'name'      => 'id',
            'id'        => true,
            'strategy'  => 'auto'
        ));

        $this->metadata->mapField(array(
            'fieldName' => 'name',
            'type'      => 'string'
        ));

        $this->metadata->mapField(array(
            'fieldName' => 'description',
            'type'      => 'string'
        ));
    }

    public function tearDown()
    {
        $this->getFilesystem()->remove($this->tmpDir);
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getTestBundle()
    {
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())->method('getPath')->will($this->returnValue($this->tmpDir));
        $bundle->expects($this->any())->method('getNamespace')->will($this->returnValue('Foo\BarBundle'));

        return $bundle;
    }
}
