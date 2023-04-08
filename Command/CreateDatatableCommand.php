<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\Command;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateDatatableCommand extends Command
{
    //-------------------------------------------------
    // The 'sg:datatable:generate' Command
    //-------------------------------------------------

    const _DATATABLE_DIR_ = 'App\\Datatables\\Datatable\\';
    const _ENTITY_DIR_ = 'App\\Entity\\';

    private $container;
    private $generator;
    public function __construct(ContainerInterface $container,string $name = null)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    public function setGenerator(Generator $generator){
        $this->generator = $generator;

    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sg:datatable:generate')
            ->setAliases(['sg:datatables:generate', 'sg:generate:datatable', 'sg:generate:datatables'])
            ->setDescription('Generates a new Datatable based on a Doctrine entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to create columns in the new Datatable.')
            ->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Allow to overwrite an existing Datatable.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $entity = $input->getArgument('entity');

        $doctrine = $this->container->get("doctrine");

        /** @var EntityManager $em */
        $em = $doctrine->getManager();


        $replacedEntity = str_replace("/","\\",$entity);
        $entityClass = self::_ENTITY_DIR_.$replacedEntity;


        $output->writeln('Entity: '.$entityClass);

        $metadata = $em->getClassMetadata($entityClass)->getMetadataValue("identifier");


        $generator = $this->generator;

        $exp = explode('/',$entity);
        $last = array_pop($exp);
        $other = implode('/',$exp);

        $className = $last."Datatable";
        $namespace = self::_DATATABLE_DIR_.''.$other;

        $datatableName = $last."_datatable";

        $generator->generateClass(self::_DATATABLE_DIR_.$entity."Datatable",'templates/command/datatable/datatable_class.tpl.php',[
            'class_namespace' => $namespace,
            'datatable_class_name' => $className,
            'entity_path' => $entityClass,
            'datatable_name' => $datatableName
        ]);

        $generator->writeChanges();

        $output->writeln('$className: '.$className);

        return Command::SUCCESS;

    }



}
