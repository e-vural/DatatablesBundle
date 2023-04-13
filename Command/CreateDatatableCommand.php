<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Command;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\File;
use Sg\DatatablesBundle\Maker\CustomAbstractClassMaker;
use Sg\DatatablesBundle\Maker\DatatableClassMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;


class CreateDatatableCommand extends AbstractMaker implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;
//
    public static function getSubscribedServices(): array
    {
        return [
            EntityManagerInterface::class,
            KernelInterface::class
        ];
    }

    //-------------------------------------------------
    // The 'sg:datatable:generate' Command
    //-------------------------------------------------


    private  function getEntityManager(): EntityManagerInterface
    {

        return $this->container->get(EntityManagerInterface::class);
    }


    public static function getCommandName(): string
    {
        return 'sg:datatable:generate';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new datatable class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {

        $command
            ->setAliases(['sg:datatables:generate', 'sg:generate:datatable', 'sg:generate:datatables'])
            ->setDescription('Generates a new Datatable based on a Doctrine entity.')
//            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to create columns in the new Datatable.')
            ->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Allow to overwrite an existing Datatable.')
        ;
    }


    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {

        /** @var EntityManager $em */
        $em = $this->getEntityManager();
        $metadataFactory = $em->getMetadataFactory();

        $io->writeln([
            'Datatable Creator',
            '============',
            '',
        ]);

        $metas = $metadataFactory->getAllMetadata();
        $selectableEntities = array();
        foreach ($metas as $meta) {
            $selectableEntities[] = $meta->getName();
        }
        $entityClassNamespace = $io->choice('Select your entity?', $selectableEntities);

        $datatableGenerator = new DatatableClassMaker();
        $datatableGenerator->generateByEntity($generator,$em,$entityClassNamespace);



        return Command::SUCCESS;
    }


    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }


    private function getTemplateVariables(){

    }
}
