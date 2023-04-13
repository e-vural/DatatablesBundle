<?= "<?php\n"; ?>

namespace <?= $class_namespace; ?>;

use Sg\DatatablesBundle\Datatable\Column\Column;
use <?= $custom_abstract_full_path; ?>;

/**
* Class  <?= $datatable_class_name; ?>
*
* @package  <?= $class_namespace; ?>
*/
class <?= $datatable_class_name; ?> extends <?= $custom_abstract_class_name; ?>
{



    /**
    * {@inheritdoc}
    */
    public function getLineFormatter()
    {
        $formatter = function($row) {


            return $row;
        };

        return $formatter;
    }


    /**
    * {@inheritdoc}
    */
    public function buildDatatable(array $options = array())
    {

        $this->setDatatableDefaults();

        $this->columnBuilder->add("",Column::class);

    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return '<?= $entity_path; ?>';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return '<?= $datatable_name; ?>';
    }
}
