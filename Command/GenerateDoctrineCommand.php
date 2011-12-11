<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;

abstract class GenerateDoctrineCommand extends DoctrineCommand
{

    protected function parseShortcutNotation($shortcut)
    {
        $document = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($document, ':')) {
            throw new \InvalidArgumentException(sprintf('The document name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $document));
        }

        return array(substr($document, 0, $pos), substr($document, $pos + 1));
    }

    /**
     * @param $name
     *
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getBundle($name)
    {
        return $this->getContainer()->get('kernel')->getBundle($name);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getDocumentNamespace($bundle)
    {
        return $this->getBundle($bundle)->getNamespace().'\\Document';
    }

    /**
     * @param $documentClass
     *
     * @return \Doctrine\ODM\MongoDB\Mapping\ClassMetadata
     */
    protected function getDocumentMetadata($documentClass)
    {
        $factory = new \Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory();
        $factory->setDocumentManager($this->getDocumentManager());
        $factory->setConfiguration($this->getDocumentManager()->getConfiguration());

        $metadata = $factory->getMetadataFor($documentClass);

        return $metadata;
    }
}
