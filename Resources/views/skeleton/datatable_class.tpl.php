<?= "<?php\n"; ?>

namespace <?= $class_namespace; ?>;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use App\Datatables\Utils\Traits\SgDatatableTrait;

/**
* Class  <?= $datatable_class_name; ?>
*
* @package  <?= $class_namespace; ?>
*/
class <?= $datatable_class_name; ?> extends AbstractDatatable
{

    use SgDatatableTrait;


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
