<?php namespace Sportlery\Library\Models;

use Hashids\Hashids;
use Model;

/**
 * Model
 */
class Location extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['description'];

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'street' => 'required',
        'email' => 'required|email',
        'zipcode' => 'required',
        'city' => 'required',
        'location_url' => 'url',
        'slug' => 'required|alpha_dash|unique:spr_locations,slug',
        'is_public' => 'required|in:0,1',
        'description' => 'required',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_locations';

    /* Relations */
    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'location_gallery' => 'System\Models\File'
    ];

    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table' => 'spr_category_location',
            'order' => 'name'
        ],
    ];

    public function getHashId()
    {
        return $this->slug.'-'.app(Hashids::class)->encode($this->getKey());
    }

    public function getCategoryNamesAttribute()
    {
        return $this->categories->lists('name');
    }

    public static function findByHashId($hashId)
    {
        $hashId = explode('-', $hashId);

        if (empty($hashId)) {
            return null;
        }

        $hashId = app(Hashids::class)->decode(end($hashId));

        if (empty($hashId)) {
            return null;
        }

        return static::find(reset($hashId));
    }
}


