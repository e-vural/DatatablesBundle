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
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;


class CreateDatatableCommand extends AbstractMaker
    implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;
//
    public static function getSubscribedServices(): array
    {
        return [
            KernelInterface::class
        ];
    }

    private EntityManagerInterface $entityManager;
    private KernelInterface $kernel;
    public function __construct(EntityManagerInterface $entityManager,KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    //-------------------------------------------------
    // The 'sg:datatable:generate' Command
    //-------------------------------------------------

    const _DATATABLE_DIR_ = 'App\\Datatables\\Datatable\\';
    const _ENTITY_DIR_ = 'App\\Entity\\';


    private  function getEntityManager(): EntityManagerInterface
    {

        return $this->entityManager;
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
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to create columns in the new Datatable.')
            ->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Allow to overwrite an existing Datatable.')
        ;
    }


    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $io->writeln([
            'Datatable Creator',
            '============',
            '',
        ]);

        $entity = $input->getArgument('entity');


        /** @var EntityManager $em */
        $em = $this->getEntityManager();


        $replacedEntity = str_replace("/","\\",$entity);
        $entityClass = self::_ENTITY_DIR_.$replacedEntity;


        $io->writeln('Entity: '.$entityClass);

        $metadata = $em->getClassMetadata($entityClass)->getMetadataValue("identifier");



        $exp = explode('/',$entity);
        $last = array_pop($exp);
        $other = implode('/',$exp);

        $className = $last."Datatable";
        $namespace = self::_DATATABLE_DIR_.''.$other;

        $datatableName = $last."_datatable";

//        $s =  $this->kernel->locateResource('@SgDatatables/skeleton/datatable_class.tpl.php');

        $generator->generateClass(self::_DATATABLE_DIR_.$entity."Datatable",__DIR__.'/../Resources/views/skeleton/datatable_class.tpl.php',[
            'class_namespace' => $namespace,
            'datatable_class_name' => $className,
            'entity_path' => $entityClass,
            'datatable_name' => $datatableName
        ]);

        $generator->writeChanges();

        $io->writeln('$className: '.$className);

        return Command::SUCCESS;
    }


    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }
}
