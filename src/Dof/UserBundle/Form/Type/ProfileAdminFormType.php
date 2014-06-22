<?php 
namespace Dof\UserBundle\Form\Type;

use Dof\UserBundle\Form\Type\ProfileFormType as BaseForm;

class ProfileAdminFormType extends BaseForm {

	private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;

        parent::__construct($class);
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);

        $builder
            ->add('point', null, array('label' => 'inputs.point', 'translation_domain' => 'profile'))
        ;
    }

}