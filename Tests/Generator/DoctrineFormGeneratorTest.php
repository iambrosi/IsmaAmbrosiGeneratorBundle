<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;

/**
 * Class DoctrineDocumentGeneratorTest.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class DoctrineFormGeneratorTest extends GeneratorTestCase
{
    public function testSimpleGenerator()
    {
        $generator = new DoctrineFormGenerator(dirname(dirname(__DIR__)).'/Resources/skeleton/form');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata);

        $this->assertFileExists($file = $this->getGeneratedFilePath());

        $content = file_get_contents($file);
        $this->assertContains('->add(\'name\')', $content);
        $this->assertContains('->add(\'description\')', $content);
        $this->assertContains('class '.$this->documentName.'Type extends AbstractType', $content);
        $this->assertContains("'data_class' => 'Foo\BarBundle\Document\\$this->documentName'", $content);
    }

    /**
     * @depends testSimpleGenerator
     */
    public function testTypeUsesLegacyOptionsConfigurator()
    {
        if (method_exists('Symfony\\Component\\Form\\AbstractType', 'configureOptions')) {
            $this->markTestSkipped('Method `configureOptions` is available in `AbstractType`.');
        }

        $generator = new DoctrineFormGenerator(dirname(dirname(__DIR__)).'/Resources/skeleton/form');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata);

        $content = file_get_contents($this->getGeneratedFilePath());

        $this->assertContains('public function setDefaultOptions(OptionsResolverInterface $resolver)', $content);
    }

    /**
     * @depends testSimpleGenerator
     */
    public function testTypeUsesNewOptionsConfigurator()
    {
        if (!method_exists('Symfony\\Component\\Form\\AbstractType', 'configureOptions')) {
            $this->markTestSkipped('Method `configureOptions` is not available in `AbstractType`.');
        }

        $generator = new DoctrineFormGenerator(dirname(dirname(__DIR__)).'/Resources/skeleton/form');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata);

        $content = file_get_contents($this->getGeneratedFilePath());

        $this->assertNotContains('public function setDefaultOptions(OptionsResolverInterface $resolver)', $content);
    }

    /**
     * @return string
     */
    private function getGeneratedFilePath()
    {
        return $this->getTestBundle()->getPath().'/Form/'.$this->documentName.'Type.php';
    }
}
