<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Class DoctrineDocumentGeneratorTest
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class DoctrineFormGeneratorTest extends GeneratorTestCase
{

    public function testSimpleGenerator()
    {
        $generator = new DoctrineFormGenerator($this->getFilesystem(), $this->getBundle()->getPath().'/Resources/skeleton/form');
        $generator->generate($this->getTestBundle(), $this->documentName, $this->metadata);

        $this->assertFileExists($file = $this->getTestBundle()->getPath().'/Form/'.$this->documentName.'Type.php');

        $content = file_get_contents($file);
        $this->assertContains('->add(\'name\')', $content);
        $this->assertContains('->add(\'description\')', $content);
        $this->assertContains('class '.$this->documentName.'Type extends AbstractType', $content);
    }
}
