<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Annotations as Utils;

/**
 * TutorialArticle
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\TutorialArticleRepository")
 */
class TutorialArticle extends Article
{
    public function isTutorialArticle() { return true; }
    public function getClass() { return 'tutorial'; }
}
