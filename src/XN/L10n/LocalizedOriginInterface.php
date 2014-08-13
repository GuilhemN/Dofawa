<?php

namespace XN\L10n;

interface LocalizedOriginInterface
{
  public function getCreatedLocale();
  public function setCreatedLocale($createdLocale);

  public function getUpdatedLocale();
  public function setUpdatedLocale($updatedLocale);
}
