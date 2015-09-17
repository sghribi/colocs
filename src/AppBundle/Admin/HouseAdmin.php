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
            ->add('address')
            ->add('members')
            ->add('latitude')
            ->add('longitude')
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
