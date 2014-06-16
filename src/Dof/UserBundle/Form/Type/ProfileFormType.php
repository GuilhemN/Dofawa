<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dof\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseForm;

class ProfileFormType extends BaseForm
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;

        parent::__construct($class);
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('guilde', null, array('label' => 'inputs.guild', 'translation_domain' => 'profile'))
            ->add('lieu', null, array('label' => 'inputs.location', 'translation_domain' => 'profile'))
            ->add('site', null, array('label' => 'inputs.website', 'translation_domain' => 'profile'))
            ->add('born', null, array('label' => 'inputs.date.born', 'translation_domain' => 'profile', 'years' => range(1,31)))
        ;
    }

    public function getName()
    {
        return 'dof_user_profile';
    }
}
