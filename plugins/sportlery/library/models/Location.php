<?php namespace Sportlery\Library\Models;

use Model;

/**
 * Model
 */
class Location extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
        'RainLab.Translate.Behaviors.TranslatableModel',
        'RainLab.Location.Behaviors.LocationModel',
        'Sportlery.Library.Behaviors.HashIdsModel',
    ];

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
            'order' => 'name',
            'scope' => 'forLocations',
        ],
        'sports' => [
            Sport::class,
            'table' => 'spr_location_sport',
            'order' => 'name',
        ],
    ];

    /**
     * Search for locations using the given parameters (available: q, sport, location_type, city)
     *
     * @param  array  $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function search(array $params)
    {
        $query = (new static)->newQuery();

        if (isset($params['q']) && $q = trim($params['q'])) {
            $q = '%'.$q.'%';

            $query->where(function($query) use ($q) {
                $query->orWhere('name', 'like', $q)
                      ->orWhere('city', 'like', $q)
                      ->orWhere('description', 'like', $q);
            });
        }

        if (isset($params['sport']) && $params['sport'] !== '') {
            $query->whereHas('sports', function($query) use ($params) {
                return $query->where('id', $params['sport']);
            });
        }

        if (isset($params['location_type']) && $params['location_type'] !== '') {
            $query->where('is_public', $params['location_type']);
        }

        if (isset($params['city']) && $city = trim($params['city'])) {
            $query->where('city', $city);
        }

        return $query;
    }

    public function beforeSave()
    {
        if (isset($this->attributes['address'])) {
            unset($this->attributes['address']);
        }
    }

    /**
     * Get an array of category names attached to the event.
     *
     * @return array
     */
    public function getCategoryNamesAttribute()
    {
        return $this->categories->lists('name');
    }

    public function getAddressAttribute()
    {
        $address = $this->street.', '.$this->city;

        if ($this->country) {
            $address .= ', '.$this->country->name;
        }

        return $address;
    }

    public function setStreetAttribute($value)
    {
        if (preg_match('/^\d+?/', $value)) {
            list($number, $street) = explode(' ', $value, 2);
            $value = trim("$street $number");
        }

        $this->attributes['street'] = $value;
    }
}


