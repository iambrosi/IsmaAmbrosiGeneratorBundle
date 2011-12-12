<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Tests\WebTestCase;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Class DoctrineDocumentGeneratorTest
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class DoctrineCrudGeneratorTest extends WebTestCase
{

    private $documentName;

    protected function setUp()
    {
        $this->pathsToRemove = array();

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->documentName = ucfirst($this->getName());
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

        $generator = new DoctrineFormGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/form');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata);
    }

    protected function tearDown()
    {
        $filesystem = $this->getFilesystem();
        $filesystem->remove($this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php');
        $filesystem->remove($this->getTestBundle()->getPath().'/Form/'.$this->documentName.'Type.php');
        $filesystem->remove($this->getTestBundle()->getPath().'/Resources/views/'.$this->documentName);
        $filesystem->remove($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.yml');
        $filesystem->remove($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.xml');
    }

    public function testAnnotation()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertFalse(method_exists($controller, 'newAction'), '"new" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'createAction'), '"create" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'deleteAction'), '"delete" action for controller does exists');

        $content = file_get_contents($file);
        $this->assertTrue(false !== strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');
    }

    public function testAnnotationWithWriteActions()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'newAction'), '"new" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'createAction'), '"create" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'deleteAction'), '"delete" action for controller does not exists');

        $content = file_get_contents($file);
        $this->assertTrue(false !== strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');
    }

    public function testYaml()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'yaml', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertFalse(method_exists($controller, 'newAction'), '"new" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'createAction'), '"create" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'deleteAction'), '"delete" action for controller does exists');

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.yml', 'Routing file does not exists');
    }

    public function testYamlWithWriteActions()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'yaml', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'newAction'), '"new" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'createAction'), '"create" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'deleteAction'), '"delete" action for controller does not exists');

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.yml', 'Routing file does not exists');
    }

    public function testXml()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'xml', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertFalse(method_exists($controller, 'newAction'), '"new" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'createAction'), '"create" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'deleteAction'), '"delete" action for controller does exists');

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.xml', 'Routing file does not exists');
    }

    public function testXmllWithWriteActions()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'xml', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$this->documentName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'newAction'), '"new" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'createAction'), '"create" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'deleteAction'), '"delete" action for controller does not exists');

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.xml', 'Routing file does not exists');
    }
}
