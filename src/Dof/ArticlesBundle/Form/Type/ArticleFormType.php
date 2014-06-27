<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dof\ArticlesBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class ArticleFormType
{
    private $request;

    public function __construct(RequestStack $requestStack) {
      $this->requestStack = $requestStack;
    }

    /**
     * Builds the embedded form representing the article.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {

      $builder
        ->add('name', null, array('label' => 'article.name', 'translation_domain' => 'entity'))
        ->add('description', 'email', array('label' => 'article.description', 'translation_domain' => 'entity'))
        ->add('categorie', null, array('label' => 'article.categorie', 'translation_domain' => 'entity'))
        ->add('keys', null, array('label' => 'article.keys', 'translation_domain' => 'entity'))
      ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'Dof\ArticlesBundle\Entity\Articles',
      ));
    }
    public function getName()
    {
      return 'dof_articles_main';
    }
}
