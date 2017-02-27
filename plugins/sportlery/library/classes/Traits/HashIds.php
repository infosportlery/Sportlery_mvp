<?php

namespace Sportlery\Library\Classes\Traits;

use Hashids\Hashids as HashidGenerator;

trait HashIds
{
    /**
     * Get the hashid for the model.
     *
     * @return string
     */
    public function getHashId()
    {
        return $this->slug.'-'.app(HashidGenerator::class)->encode($this->getKey());
    }

    /**
     * Find a model instance by it's hash id.
     *
     * @param  string  $hashId
     * @return \October\Rain\Database\Model|null
     */
    public static function findByHashId($hashId)
    {
        $hashId = explode('-', $hashId);

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
