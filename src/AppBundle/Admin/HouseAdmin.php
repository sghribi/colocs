<?php

namespace AppBundle\Admin;

use AppBundle\Entity\House;
use AppBundle\Entity\User;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class HouseAdmin
 */
class HouseAdmin extends Admin
{
    protected $baseRouteName = 'sonata_house';
    protected $baseRoutePattern = 'houses';

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('summary')
            ->add('type')
            ->add('address')
            ->add('members')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper

            ->add('name')
            ->add('summary')
            ->add('type')
            ->add('members', 'sonata_type_model_autocomplete', array(
                    'property' => 'email',
                    'multiple' => true,
                )
            )
            ->add('address', null, array(
                'label' => 'Adresse qui sera affichée dans l\'infobulle (y ajouter des infos complémentaires par exemple)',
            ))
            ->add('latlng', 'oh_google_maps', array(
                    'label'             => 'Localisation (uniquement pour la recherche, ne sera pas affiché dans l\'infobulle)',
                    'type'              => 'text',
                    'options'           => array(), // the options for both the fields
                    'lat_options'       => array(
                        'label' => 'Latitude',
                    ), // the options for just the lat field
                    'lng_options'       => array(
                        'label' => 'Longitude',
                    ), // the options for just the lng field
                    'lat_name'          => 'lat',   // the name of the lat field
                    'lng_name'          => 'lng',   // the name of the lng field
                    'map_width'         => 800,     // the width of the map
                    'map_height'        => 500,     // the height of the map
                    'default_lat'       => 48.765165,    // the starting position on the map
                    'default_lng'       => 2.288402, // the starting position on the map
                    'include_jquery'    => false,   // jquery needs to be included above the field (ie not at the bottom of the page)
                    'include_gmaps_js'  =>true     // is this the best place to include the google maps javascript?
                )
            )
        ;
    }
    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('summary')
            ->add('type')
            ->add('address')
            ->add('members')
            ->add('latitude')
            ->add('longitude')
        ;
    }

    /**
     * @param House $object
     */
    public function preUpdate($object)
    {
        /** @var User $media */
        foreach ($object->getMembers() as $user) {
            $user->setHouse($object);
        }
    }

    /**
     * @param House $object
     */
    public function prePersist($object)
    {
        /** @var User $media */
        foreach ($object->getMembers() as $user) {
            $user->setHouse($object);
        }
    }
}
