<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Maker;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Generator;

class DatatableClassMaker
{

    const _DATATABLE_DIR_ = 'App\\Datatables\\Datatable';


    public function generateByEntity(Generator $generator,EntityManagerInterface $em, $entityClassNamespace){



        $datatableGenerator = new CustomAbstractClassMaker();
        $customAbstractResponse = $datatableGenerator->generate($generator);

        $metadataFactory = $em->getMetadataFactory();
//        $isValid = $metadataFactory->hasMetadataFor($entityClassNamespace);
        $classMetaData = $metadataFactory->getMetadataFor($entityClassNamespace);
        $entityClassName = $classMetaData->getReflectionClass()->getShortName();

        $entityNamespaces = $em->getConfiguration()->getEntityNamespaces();
        foreach ($entityNamespaces as $entityNamespace){
            $entityClassDir = str_replace($entityNamespace,"",$entityClassNamespace);
        }

        $exp = explode('\\',$entityClassDir);
        array_pop($exp);
        $other = implode('\\',$exp);
        $className = $entityClassName."Datatable";
        $namespace = self::_DATATABLE_DIR_.''.$other;

        $datatableName = $entityClassName."_datatable";

        $generator->generateClass($namespace."\\".$className,$this->getClassSkeletonPath(),[
            'class_namespace' => $namespace,
            'datatable_class_name' => $className,
            'entity_path' => $entityClassNamespace,
            'datatable_name' => $datatableName,
            'custom_abstract_class_name' => $customAbstractResponse["className"],
            'custom_abstract_full_path' => $customAbstractResponse["classFullPath"]
        ]);

        $generator->writeChanges();

//        $io->writeln('$className: '.$className);
    }

    private function getClassSkeletonPath(){
        return __DIR__.'/../Resources/views/skeleton/datatable_class.tpl.php';
    }


}
