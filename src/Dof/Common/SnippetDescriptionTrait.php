<?php

namespace Dof\Common;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait SnippetDescriptionTrait
{
    /**
     * @var ContainerInterface
     */
    private $di;

    /**
     * Set container.
     *
     * @param ContainerInterface $di
     *
     * @return object
     */
    public function setContainer(ContainerInterface $di)
    {
        $this->di = $di;

        return $this;
    }

    /**
     * Get container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->di;
    }

    public function getPlainTextDescription($locale = 'fr', $full = false, $technical = false)
    {
        $translator = $this->di->get('translator');

        return implode('', array_map(function (array $row) use ($translator) {
            if ($row[0] instanceof LocalizedNameTrait) {
                return $row[0]->getName($translator->getLocales());
            } else {
                return $row[0];
            }
        }, $this->getDescription($locale, $full, $technical)));
    }

    public function getHtmlDescription()
    {
        $securityContext = $this->di->get('security.authorization_checker');
        $translator = $this->di->get('translator');
        $router = $this->di->get('router');

        return implode('', array_map(function (array $row) use ($translator, $router, $securityContext) {
            if ($row[1] === GameTemplateString::COMES_FROM_TEMPLATE) {
                return htmlspecialchars($row[0]);
            } else {
                if ($row[0] instanceof \XN\L10n\LocalizedNameInterface) {
                    $name = $row[0]->getName($translator->getLocales());
                } elseif (method_exists($row[0], '__toStringByLocale')) {
                    $name = $row[0]->__toStringByLocale($translator->getLocales());
                } else {
                    $name = $row[0];
                }

                if ($row[0] instanceof \Dof\Bundle\CharacterBundle\Entity\Spell && ($row[0]->isPubliclyVisible() or $securityContext->isGranted('ROLE_SPELL_XRAY'))) {
                    return '<a href="'.$router->generate('dof_spell_show', array('slug' => $row[0]->getSlug())).'">'.$name.'</a>';
                } elseif ($row[0] instanceof \Dof\Bundle\CharacterBundle\Entity\Breed) {
                    return '<a href="'.$router->generate('dof_characters_show', array('slug' => $row[0]->getSlug())).'">'.$name.'</a>';
                } elseif ($row[0] instanceof \Dof\Bundle\MonsterBundle\Entity\Monster) {
                    return '<a href="'.$router->generate('dof_monster_show', array('slug' => $row[0]->getSlug())).'">'.$name.'</a>';
                } elseif ($row[0] instanceof \Dof\Bundle\ItemBundle\Entity\ItemTemplate) {
                    return '<a href="'.$router->generate('dof_items_show', array('slug' => $row[0]->getSlug())).'">'.$name.'</a>';
                } else {
                    return $name;
                }
            }
        }, $this->getDescription($translator->getLocale(), $securityContext->isGranted('ROLE_XRAY'), false)));
    }
}
