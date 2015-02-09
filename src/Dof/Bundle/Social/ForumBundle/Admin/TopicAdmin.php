<?php

namespace Dof\Bundle\Social\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

use Dof\Bundle\Social\ForumBundle\Form\MessageType;

class TopicAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('forum')
            ->add('name')
            ->add('locked')
            ->add('createdAt')
            ->add('slug')
            ->add('createdOnLocale')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('forum')
            ->add('owner')
            ->add('updater')
            ->add('name')
            ->add('locked')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('createdOnLocale')
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
            ->add('forum')
            ->add('owner')
            ->add('updater')
            ->add('name')
            ->add('locked', null, array('required' => false))
            ->add('messages')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('forum')
            ->add('name')
            ->add('locked')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('createdOnLocale')
            ->add('updatedOnLocale')
        ;
    }

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
      $collection->remove('create');
    }
}