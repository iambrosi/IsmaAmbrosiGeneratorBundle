<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

/**
 * Class DoctrineDocumentGeneratorTest
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class DoctrineDocumentGeneratorTest extends WebTestCase
{

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    protected function tearDown()
    {
        $this->getFilesystem()->remove($this->getBundle()->getPath() . DIRECTORY_SEPARATOR . 'Document');
    }

    public function testSimpleGenerator()
    {
        $generator = new DoctrineDocumentGenerator($this->getFilesystem(), $this->getDocumentManager());

        $name = ucfirst(__FUNCTION__);
        $generator->generate(
            $bundle = $this->getBundle(),
            $name,
            array(
                 array('fieldName'=> 'myField',
                       'type'     => 'string')
            ), false
        );

        $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . '.php');
        $this->assertFileNotExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . 'Repository.php');
        $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name));
        $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name), 'Document class does not exists');
        $this->assertFalse(class_exists($bundle->getNamespace() . '\\Document\\' . $name . 'Repository'), 'Repository class does exists');
        $this->assertClassHasAttribute('myField', $bundle->getNamespace() . '\\Document\\' . $name, 'Class does not have the specified attribute');
    }

    public function testSimpleGeneratorWithRepository()
    {
        $generator = new DoctrineDocumentGenerator($this->getFilesystem(), $this->getDocumentManager());

        $name = ucfirst(__FUNCTION__);
        $generator->generate(
            $bundle = $this->getBundle(),
            $name,
            array(
                 array('fieldName'=> 'myField',
                       'type'     => 'string')
            ), true
        );

        $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . '.php');
        $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . 'Repository.php');
        $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name), 'Document class does not exists');
        $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name . 'Repository'), 'Repository class does not exists');
        $this->assertClassHasAttribute('myField', $bundle->getNamespace() . '\\Document\\' . $name, 'Class does not have the specified attribute');
    }

    public function testFieldType()
    {
        $generator = new DoctrineDocumentGenerator($this->getFilesystem(), $this->getDocumentManager());

        foreach (array_keys(Type::getTypesMap()) as $type) {
            $name = ucfirst(__FUNCTION__) . ucfirst(md5($type));
            $generator->generate(
                $bundle = $this->getBundle(),
                $name,
                array(
                     array('fieldName'=> 'myField',
                           'type'     => $type)
                ), true
            );

            $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . '.php');
            $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . $name . 'Repository.php');
            $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name), 'Document class does not exists');
            $this->assertTrue(class_exists($bundle->getNamespace() . '\\Document\\' . $name . 'Repository'), 'Repository class does not exists');
            $this->assertClassHasAttribute('myField', $bundle->getNamespace() . '\\Document\\' . $name, 'Class does not have the specified attribute');
        }
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    private function getBundle()
    {
        return static::$kernel->getBundle('IsmaAmbrosiGeneratorBundle');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDocumentManager()
    {
        return static::$kernel->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Util\Filesystem
     */
    private function getFilesystem()
    {
        return static::$kernel->getContainer()->get('filesystem');
    }
}
