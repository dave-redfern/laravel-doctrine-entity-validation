<?php

/**
 * Class MyEntityRules
 *
 * @package    _data
 * @subpackage _data\MyEntityRules
 * @author     Dave Redfern
 */
class MyEntityEntityRules extends \Somnambulist\EntityValidation\AbstractEntityRules
{

    /**
     * @param object $entity
     *
     * @return array
     */
    protected function buildRules($entity)
    {
        return [
            'name'      => 'required|min:1|string',
            'createdAt' => 'required',
        ];
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    public function supports($entity)
    {
        return $entity instanceof MyEntity;
    }
}
