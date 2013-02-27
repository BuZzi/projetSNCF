<?php
/**
 * Created by ardietr
 * Date: 27/02/13
 * Time: 11:42
 */

namespace LPDW\Bundle\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchStationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $_oBuilder, array $option)
    {
        $_oBuilder
            ->add('stationName', 'text', array(
            'label' => 'Nom de la gare'
        ));
    }

    public function getName()
    {
        return 'station_search';
    }

}
