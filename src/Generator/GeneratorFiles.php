<?php

namespace WsdlToPhp\PackageGenerator\Generator;

use WsdlToPhp\PackageGenerator\Model\Struct as StructModel;
use WsdlToPhp\PackageGenerator\Model\EmptyModel;
use WsdlToPhp\PackageGenerator\File\Struct as StructFile;
use WsdlToPhp\PackageGenerator\File\StructArray as StructArrayFile;
use WsdlToPhp\PackageGenerator\File\StructEnum as StructEnumFile;
use WsdlToPhp\PackageGenerator\File\Service as ServiceFile;
use WsdlToPhp\PackageGenerator\File\Tutorial as TutorialFile;
use WsdlToPhp\PackageGenerator\File\ClassMap as ClassMapFile;
use WsdlToPhp\PackageGenerator\File\Composer as ComposerFile;

class GeneratorFiles extends AbstractGeneratorAware
{
    /**
     * Use classmap file object
     * @var ClassMapFile
     */
    private $classmapFile;
    /**
     * @return ClassMapFile
     */
    public function getClassmapFile()
    {
        if (!isset($this->classmapFile)) {
            $classMapModel = new EmptyModel($this->generator, 'ClassMap');
            $classMap = new ClassMapFile($this->generator, $classMapModel->getPackagedName());
            $classMap->setModel($classMapModel);
            $this->classmapFile = $classMap;
        }
        return $this->classmapFile;
    }
    /**
     * @return GeneratorFilesFiles
     */
    public function doGenerate()
    {
        return $this
            ->generateStructsClasses()
            ->generateServicesClasses()
            ->generateClassMap()
            ->generateTutorialFile()
            ->generateComposerFile();
    }

    /**
     * Generates structs classes based on structs collected
     * @return GeneratorFiles
     */
    private function generateStructsClasses()
    {
        foreach ($this->getGenerator()->getStructs() as $struct) {
            if (!$struct->getIsStruct()) {
                continue;
            }
            $this
                ->getStructFile($struct)
                ->setModel($struct)
                ->write();
        }
        return $this;
    }
    /**
     * @param StructModel $struct
     * @return StructEnumFile|StructArrayFile|StructFile
     */
    private function getStructFile(StructModel $struct)
    {
        if ($struct->getisRestriction()) {
            $file = new StructEnumFile($this->generator, $struct->getPackagedName());
        } elseif ($struct->isArray()) {
            $file = new StructArrayFile($this->generator, $struct->getPackagedName());
        } else {
            $file = new StructFile($this->generator, $struct->getPackagedName());
        }
        return $file;
    }
    /**
     * Generates methods by class
     * @return GeneratorFiles
     */
    private function generateServicesClasses()
    {
        foreach ($this->getGenerator()->getServices() as $service) {
            /**
             * Generates file
             */
            $file = new ServiceFile($this->generator, $service->getPackagedName());
            $file
                ->setModel($service)
                ->write();
        }
        return $this;
    }
    /**
     * Generates classMap class
     * @return GeneratorFiles
     */
    private function generateClassMap()
    {
        $this
            ->getClassmapFile()
            ->write();
        return $this;
    }
    /**
     * Generates tutorial file
     * @return GeneratorFiles
     */
    private function generateTutorialFile()
    {
        if ($this->generator->getOptionGenerateTutorialFile() === true && $this->getClassmapFile() instanceof ClassMapFile) {
            $tutorialFile = new TutorialFile($this->generator, 'tutorial');
            $tutorialFile->write();
        }
        return $this;
    }
    /**
     * @throws \InvalidArgumentException
     * @return GeneratorFiles
     */
    private function generateComposerFile()
    {
        if ($this->generator->getOptionStandalone() === true) {
            $composerFile = new ComposerFile($this->generator, 'composer');
            $composerFile->write();
        }
        return $this;
    }
}
