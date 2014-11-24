<?php
namespace Dof\CharactersBundle;

use Symfony\Component\Translation\TranslatorInterface;

use Dof\CharactersBundle\Entity\EffectTemplate;

interface EffectInterface
{
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

    public function getTranslator();
    public function setTranslator(TranslatorInterface $translator);
}