<?php 
namespace Dof\AdminBundle\Form\Type;

use Dof\UserBundle\Form\Type\ProfileFormType as BaseForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileAdminFormType extends BaseForm {

	private $class;

	public function __construct($class = 'Dof\UserBundle\Entity\User'){
		parent::__construct($class);
		$this->class = $class;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	parent::buildUserForm($builder, $options);

        $builder
        ->add('point', null, array('label' => 'inputs.point', 'translation_domain' => 'profile'))
        ;
    }

    public function getName()
    {
        return 'dofadmin_user_profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
	        'data_class' => $this->class,
        ));
    }

}