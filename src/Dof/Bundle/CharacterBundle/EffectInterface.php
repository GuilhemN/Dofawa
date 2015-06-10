<?php

namespace Dof\Bundle\CharacterBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Dof\Bundle\CharacterBundle\Entity\EffectTemplate;

interface EffectInterface
{
    public function getDescription($locale = 'fr', $full = false);
    public function getPlainTextDescription($locale = 'fr');
    public function getHtmlDescription();

    public function getEffectTemplate();
    public function setEffectTemplate(EffectTemplate $effectTemplate);

    public function getParam1();
    public function setParam1($param1);

    public function getParam2();
    public function setParam2($param2);

    public function getParam3();
    public function setParam3($param3);

    public function getFragments();
    public function addFragment($fragment);
    public function removeFragment($fragment);

    public function getContainer();
    public function setContainer(ContainerInterface $di);
}
