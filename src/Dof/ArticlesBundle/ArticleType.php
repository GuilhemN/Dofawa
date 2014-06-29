<?php

namespace Dof\ArticlesBundle;

class ArticleType
{
  const NONE = 0;
  const TUTORIAL = 1;
  const QUEST = 2;
  const DUNGEON = 3;
  const NEWS = 4;

  private function __construct() { }

  public static function isValid($type)
  {
    return $type >= 0 && $type <= 4;
  }
}
