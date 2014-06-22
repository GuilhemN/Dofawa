<?php 
namespace Dof\AdminBundle\Form\Type;

use Dof\UserBundle\Form\Type\ProfileFormType as BaseForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderFormBuilderInterface;

class ProfileAdminFormType extends AbstractType {

	private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('otherForm', new BaseForm())
            ->add('point', null, array('label' => 'inputs.point', 'translation_domain' => 'profile'))
        ;
    }

    public function getName()
    {
        return 'dofadmin_user_profile';
    }

}