<?php

namespace XN\L10n;

interface LocalizedOriginInterface
{
    public function getCreatedOnLocale();
    public function setCreatedOnLocale($createdOnLocale);

    public function getUpdatedOnLocale();
    public function setUpdatedOnLocale($updatedOnLocale);
}
