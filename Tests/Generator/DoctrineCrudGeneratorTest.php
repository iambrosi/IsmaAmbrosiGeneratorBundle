<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Class DoctrineDocumentGeneratorTest
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class DoctrineCrudGeneratorTest extends GeneratorTest
{

    public function testAnnotation()
    {
        $prefix = 'test/admin/'.strtolower($this->getName());
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

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
        require_once $file;

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
        require_once $file;

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
        require_once $file;

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
        require_once $file;

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
        require_once $file;

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
