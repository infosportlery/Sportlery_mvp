<?php namespace Sportlery\Library\Models;

use Hashids\Hashids;
use Model;

/**
 * Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['description'];

    /*
     * Validation
     */
    public $rules = [
        'slug' => 'required|unique:spr_events,slug',
        'starts_at' => 'required|date_format:"Y-m-d H:i:s"',
        'ends_at' => 'required|date_format:"Y-m-d H:i:s"',
        'description' => 'required',
        'price' => 'required|integer',
        'location_id' => 'required|exists:spr_locations,id',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_events';

    /* Relations */
    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'event_gallery' => 'System\Models\File'
    ];

    public $belongsTo = [
        'location' => Location::class,
    ];

    public $belongsToMany = [
        'sports' => [
            Sport::class,
            'table' => 'spr_event_sport',
            'order' => 'name',
        ],
        'categories' => [
            Category::class,
            'table' => 'spr_category_event',
            'order' => 'name',
            'scope' => 'forEvents',
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


