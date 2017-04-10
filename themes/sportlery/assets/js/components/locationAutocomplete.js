class LocationAutocomplete {
    constructor(element) {
        this.$element = $(element);
        this.init();
    }

    init() {
        this.autocomplete = new google.maps.places.Autocomplete(this.$element.get(0), {
            types: ['address']
        })

        google.maps.event.addListener(this.autocomplete, 'place_changed', $.proxy(this.handlePlaceChanged, this));

        // Prevent ENTER from submitting form
        this.$element.bind('keypress keydown keyup', function(e){
            if (e.keyCode == 13) { e.preventDefault() }
        });
    }

    handlePlaceChanged() {
        const place = this.autocomplete.getPlace();
        const geoLocation = place.geometry.location;

        const result = {
            latitude: geoLocation.lat(),
            longitude: geoLocation.lng(),
            street: `${this.getAddressValue(place, 'route')} ${this.getAddressValue(place, 'street_number')}`,
            city: this.getAddressValue(place, 'locality'),
            zip_code: this.getAddressValue(place, 'postal_code'),
            state: this.getAddressValue(place, 'administrative_area_level_1', 'long_name'),
            country: this.getAddressValue(place, 'country', 'long_name'),
        };

        for (let key in result) {
            $(`[name="${key}"]`).val(result[key]);
        }
    }

    getAddressValue(addressObj, type, resultType = 'short_name') {
        let result = null;

        if (!addressObj) {
            return null;
        }

        for (var i = 0; i < addressObj.address_components.length; i++) {
            for (var j = 0; j < addressObj.address_components[i].types.length; j++) {
                if (addressObj.address_components[i].types[j] == type) {
                    result = addressObj.address_components[i][resultType]
                    break;
                }
            }

            if (result) {
                break;
            }
        }

        return result;
    }
}

$.fn.locationAutocomplete = function () {
    this.each(function () {
        $(this).data('plugin_location_autocomplete', new LocationAutocomplete(this));
    });
};
