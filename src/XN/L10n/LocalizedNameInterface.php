<?php

namespace XN\L10n;

interface LocalizedNameInterface
{
    public function setNameFr($nameFr);
    public function getNameFr();
    public function setNameEn($nameEn);
    public function getNameEn();
    public function setNameDe($nameDe);
    public function getNameDe();
    public function setNameEs($nameEs);
    public function getNameEs();
    public function setNameIt($nameIt);
    public function getNameIt();
    public function setNamePt($namePt);
    public function getNamePt();
    public function setNameJa($nameJa);
    public function getNameJa();
    public function setNameRu($nameRu);
    public function getNameRu();

    public function setName($name, $locale = 'fr');
    public function getName($locale = 'fr');

    public function getNames();
}
