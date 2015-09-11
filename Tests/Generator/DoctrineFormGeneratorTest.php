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

        $this->assertFileExists($file = $this->getTestBundle()->getPath().'/Form/'.$this->documentName.'Type.php');

        $content = file_get_contents($file);
        $this->assertContains('->add(\'name\')', $content);
        $this->assertContains('->add(\'description\')', $content);
        $this->assertContains('class '.$this->documentName.'Type extends AbstractType', $content);
        $this->assertContains("'data_class' => 'Foo\BarBundle\Document\\$this->documentName'", $content);
    }
}
