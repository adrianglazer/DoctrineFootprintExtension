<?php

namespace Glazer\DoctrineFootprintExtension\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class FootprintFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (in_array('deletedAt', $targetEntity->getFieldNames())) {
            return $targetTableAlias . '.deleted_at is NULL';
        }

        return '';
    }
}