<?php
namespace Dof\Bundle\SearchBundle\Intent;

interface IntentInterface
{
    public function process(array $entities, $intent);
}
