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


use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Filesystem\Filesystem;

class CustomAbstractClassMaker
{


    public function generate(Generator $generator){

        $classNamespace = DatatableClassMaker::_DATATABLE_DIR_;
        $className = "CustomAbstractDatatable";
        $classFullPath = $classNamespace."\\".$className;

        try{

            $generator->generateClass($classFullPath,$this->getClassSkeletonPath(),[
                'class_namespace' => $classNamespace,
                'datatable_class_name' => $className,
            ]);

            $generator->writeChanges();
        }catch(\Exception $exception){

        }



        return ["classFullPath" => $classFullPath,"className" => $className];

    }

    private function getClassSkeletonPath(){
        return __DIR__.'/../Resources/views/skeleton/datatable_custom_abstract.tpl.php';
    }



}
