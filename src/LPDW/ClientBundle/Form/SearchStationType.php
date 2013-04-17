<?php
/**
 * Created by ardietr
 * Date: 27/02/13
 * Time: 11:42
 */

namespace LPDW\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchStationType extends AbstractType
{
    /**
     * Ajoute les champs au formulaire
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $_oBuilder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $_oBuilder, array $option)
    {
        $_oBuilder
            ->add('name', 'text', array(
                'attr'=> array(
                        'placeholder' => 'Nom de la gare',
                        'class' => 'large-12 small-12 columns'
                    )
            ));
    }

    public function getName()
    {
        return 'station_search';
    }
}
