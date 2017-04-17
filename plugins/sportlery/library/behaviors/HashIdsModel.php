<?php

namespace Sportlery\Library\Behaviors;

use System\Classes\ModelBehavior;
use Hashids\Hashids as HashidGenerator;

class HashIdsModel extends ModelBehavior
{
    /**
     * Add a where clause to the query for a given hashid.
     * e.g. `Location::whereHashId('hashid')->first();`
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $hashId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereHashId($query, $hashId)
    {
        return $query->where($this->model->getQualifiedKeyName(), '=', $this->decodeHashId($hashId));
    }

    /**
     * Find a model instance by it's hash id.
     *
     * @param  string  $hashId
     * @return \October\Rain\Database\Model|null
     */
    public function findByHashId($hashId)
    {
        return $this->model->find($this->decodeHashId($hashId));
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

    /**
     * Decode the given hashid into a regular database id.
     *
     * @param  string  $hashId
     * @return int|null
     */
    public function decodeHashId($hashId)
    {
        $hashId = static::getHashIdPrefixColumn() ? explode('-', $hashId) : [$hashId];

        if (empty($hashId)) {
            return null;
        }

        $hashId = app(HashidGenerator::class)->decode(end($hashId));

        return count($hashId) > 0 ? reset($hashId) : null;
    }
}
