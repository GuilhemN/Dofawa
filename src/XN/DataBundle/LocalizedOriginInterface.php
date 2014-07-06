<?php

namespace XN\DataBundle;

interface LocalizedOriginInterface
{
  public function getCreatedLocale();
  public function setCreatedLocale($createdLocale);

  public function getUpdatedLocale();
  public function setUpdatedLocale($updatedLocale);
}
