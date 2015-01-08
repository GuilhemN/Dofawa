<?php
namespace Dof\SearchBundle\Intent;

interface IntentInterface
{
    public function process(array $entities, $intent);
}
