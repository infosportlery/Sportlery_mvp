<?php

namespace Sportlery\Library\Classes\Traits;

use Hashids\Hashids as HashidGenerator;

trait HashIds
{
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
        $prefix = $prefixColumn ? $this->attributes[$prefixColumn] : null;
        $hashId = app(HashidGenerator::class)->encode($this->getKey());

        return ($prefix ? $prefix.'-' : '').$hashId;
    }

    /**
     * Find a model instance by it's hash id.
     *
     * @param  string  $hashId
     * @return \October\Rain\Database\Model|null
     */
    public static function findByHashId($hashId)
    {
        $hashId = static::getHashIdPrefixColumn() ? explode('-', $hashId) : [$hashId];

        if (empty($hashId)) {
            return null;
        }

        $hashId = app(HashidGenerator::class)->decode(end($hashId));

        if (empty($hashId)) {
            return null;
        }

        return static::find(reset($hashId));
    }
}
