<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Generator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class DoctrineFormGenerator.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class DoctrineFormGenerator extends Generator
{
    /**
     * @var string
     */
    private $skeletonDir;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $classPath;

    /**
     * @param string $skeletonDir
     */
    public function __construct($skeletonDir)
    {
        $this->setSkeletonDirs($skeletonDir);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassPath()
    {
        return $this->classPath;
    }

    /**
     * Generates the document form class if it does not exist.
     *
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface $bundle
     * @param string                                               $document
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo      $metadata
     *
     * @throws \RuntimeException
     */
    public function generate(BundleInterface $bundle, $document, ClassMetadataInfo $metadata)
    {
        $parts = explode('\\', $document);
        $class = array_pop($parts);

        $this->className = $class.'Type';
        $dirPath = $bundle->getPath().'/Form';
        $this->classPath = $dirPath.'/'.str_replace('\\', '/', $document).'Type.php';

        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s form class as it already exists under the %s file', $this->className, $this->classPath));
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support document classes with multiple primary keys.');
        }

        $parts = explode('\\', $document);
        array_pop($parts);

        $this->renderFile('FormType.php.twig', $this->classPath, array(
            'dir' => $this->skeletonDir,
            'fields' => $this->getFieldsFromMetadata($metadata),
            'namespace' => $bundle->getNamespace(),
            'document_class' => $class,
            'document_namespace' => implode('\\', $parts),
            'form_class' => $this->className,
            'form_type_name' => strtolower(str_replace('\\', '_', $bundle->getNamespace()).($parts ? '_' : '').implode('_', $parts).'_'.$this->className),
        ));
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $metadata
     *
     * @return array
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->getFieldNames();

        // Remove the primary key field if it's not managed manually
        if ($metadata->isIdGeneratorAuto()) {
            $fields = array_diff($fields, array($metadata->identifier));
        }

        return $fields;
    }
}
