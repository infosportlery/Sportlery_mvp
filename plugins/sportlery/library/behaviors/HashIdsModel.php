<?php

namespace Sportlery\Library\Behaviors;

use System\Classes\ModelBehavior;
use Hashids\Hashids as HashidGenerator;

class HashIdsModel extends ModelBehavior
{
    /**
     * Find a model instance by it's hash id.
     *
     * @param  string  $hashId
     * @return \October\Rain\Database\Model|null
     */
    public function findByHashId($hashId)
    {
        $hashId = static::getHashIdPrefixColumn() ? explode('-', $hashId) : [$hashId];

        if (empty($hashId)) {
            return null;
        }

        $hashId = app(HashidGenerator::class)->decode(end($hashId));

        if (empty($hashId)) {
            return null;
        }

        return $this->model->find(reset($hashId));
    }

    /**
     * Specify which column is used for the hash id prefix.
     *
     * @return string
     */
    protected static function getHashIdPrefixColumn()
    {
        return 'slug';
    }

    /**
     * Get the hashid for the model.
     *
     * @return string
     */
    public function getHashId()
    {
        $prefixColumn = static::getHashIdPrefixColumn();
        $prefix = $prefixColumn ? $this->model->attributes[$prefixColumn] : null;
        $hashId = app(HashidGenerator::class)->encode($this->model->getKey());

        return ($prefix ? $prefix.'-' : '').$hashId;
    }
}
