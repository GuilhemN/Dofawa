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

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseForm;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class RegistrationFormType extends BaseForm
{

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      parent::buildForm($builder, $options);

      $builder->add('recaptcha', 'ewz_recaptcha', array(
          'attr'          => array(
              'options' => array(
                  'theme' => 'clean'
              )
          ),
          'mapped' => false,
          'constraints'   => array(
              new True()
          )
      ));
    }

    public function getName()
    {
        return 'dof_user_registration';
    }
}
