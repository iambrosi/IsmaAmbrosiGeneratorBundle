<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;

/**
 * Class DoctrineDocumentGeneratorTest.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class DoctrineCrudGeneratorTest extends GeneratorTestCase
{
    public function testAnnotation()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithoutWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false !== strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');
    }

    /**
     * @depends testAnnotation
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unable to generate the controller as it already exists.
     */
    public function testDisallowGeneratingExistingDocuments()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, false);
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, false);
    }

    public function testAnnotationWithWriteActions()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false !== strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');
    }

    public function testYaml()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'yaml', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithoutWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.yml', 'Routing file does not exists');
    }

    public function testYamlWithWriteActions()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'yaml', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.yml', 'Routing file does not exists');
    }

    public function testXml()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'xml', $prefix, false);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithoutWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.xml', 'Routing file does not exists');
    }

    public function testXmlWithWriteActions()
    {
        $prefix = $this->getPathPrefix();
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'xml', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false === strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/config/routing/'.strtolower($this->documentName).'.xml', 'Routing file does not exists');
    }

    public function testGeneratorWithInvalidCharsOnRoutePrefix()
    {
        $prefix = 'my-very-strange-prefix';
        $generator = new DoctrineCrudGenerator($this->getFilesystem(), __DIR__.'/../../Resources/skeleton/crud');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata, 'annotation', $prefix, true);

        $file = $this->getTestBundle()->getPath().'/Controller/'.$this->documentName.'Controller.php';
        $classContent = file_get_contents($file);

        $this->assertFalse((bool) preg_match('/.*\@Route\(\"([\w\/\-\{\}]+)\", name="([^\w\_]+)"\).*/', $classContent));

        $this->assertFileExists($file, 'Controller class file does not exists');
        require_once $file;

        $namespace = $this->getTestBundle()->getNamespace();
        $this->assertControllerWithWriteActions($namespace, $this->documentName);

        $content = file_get_contents($file);
        $this->assertTrue(false !== strpos($content, '@Route("/'.$prefix.'")'), 'Route annotation not found in class');
    }

    protected function assertControllerWithWriteActions($namespace, $controllerName)
    {
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$controllerName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'newAction'), '"new" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'createAction'), '"create" action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'deleteAction'), '"delete" action for controller does not exists');

        $controllerPath = str_replace('\\', '/', $controllerName);
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/index.html.twig');
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/show.html.twig');
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/new.html.twig');
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/edit.html.twig');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Tests/Controller/'.$controllerPath.'ControllerTest.php');
    }

    protected function assertControllerWithoutWriteActions($namespace, $controllerName)
    {
        $this->assertTrue(class_exists($controller = $namespace.'\\Controller\\'.$controllerName.'Controller'), 'Controller class does not exists');
        $this->assertTrue(method_exists($controller, 'indexAction'), 'Index action for controller does not exists');
        $this->assertTrue(method_exists($controller, 'showAction'), 'Show action for controller does not exists');
        $this->assertFalse(method_exists($controller, 'newAction'), '"new" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'createAction'), '"create" action for controller does exists');
        $this->assertFalse(method_exists($controller, 'deleteAction'), '"delete" action for controller does exists');

        $controllerPath = str_replace('\\', '/', $controllerName);
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/index.html.twig');
        $this->assertFileExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/show.html.twig');
        $this->assertFileNotExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/new.html.twig');
        $this->assertFileNotExists($this->getTestBundle()->getPath().'/Resources/views/'.$controllerPath.'/edit.html.twig');

        $this->assertFileExists($this->getTestBundle()->getPath().'/Tests/Controller/'.$controllerPath.'ControllerTest.php');
    }

    private function getPathPrefix()
    {
        return 'test/admin/'.strtolower($this->getName());
    }
}
