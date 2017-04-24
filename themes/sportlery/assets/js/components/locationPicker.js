class LocationPicker {

    constructor(element) {
        if (!window.L) {
            throw new Error('Leaflet must be included to use the location picker.');
        }

        this.$element = $(element);
        this.$target = $(this.$element.data('target'));
        this.$mapElement = this.$element.find('[data-map]');
        this.map = L.map(this.$mapElement[0]);
        this.initTiles();

        if (this.$element.closest('.modal').length) {
            this.$element.closest('.modal').on('shown.bs.modal', () => {
                if (this.marker) {
                    this.marker.remove();
                }
                this.resetBounds();
            });
        } else {
            this.resetBounds();
        }
            this.$element.find('[href="#tab-map"]').on('shown.bs.tab', () => {
                this.resetBounds();
            });
        this.marker = null;
        this.map.on('click', (e) => {
            var latlng = e.latlng;
            var latitude = latlng.lat;
            var longitude = latlng.lng;
            this.$element.find('[name=latitude]').val(latitude);
            this.$element.find('[name=longitude]').val(longitude);

            if (this.marker === null) {
                this.marker = L.marker([latitude, longitude]).addTo(this.map);
            } else {
                this.marker.setLatLng([latitude, longitude]);
            }
        });

        this.$element.find('form').on('submit', (e) => {
            e.preventDefault();
            var $form = $(e.target);
            var handler = $form.find('[name=_handler]').val();
            $form.request(handler, {
                success: (data) => {
                    var $option = $('<option>').val(data.id).text(data.name);
                    $option.appendTo(this.$target);
                    this.$target.val(data.id);
                    if (this.$element.closest('.modal').length) {
                        this.$element.closest('.modal').modal('hide');
                    }
                }
            });
        });
    }

    resetBounds() {
        this.map.invalidateSize();
        if (this.$element.data('initial-lat') && this.$element.data('initial-lng')) {
            this.map.setView([this.$element.data('initial-lat'), this.$element.data('initial-lng')], 13);
        } else {
            this.map.fitBounds([
                [50.7504, 3.3316],
                [53.6316, 7.2275]
            ]);
        }
    }

    /**
     * Initialize the tile layer and add it to the map.
     */
    initTiles() {
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
    }

}

$.fn.locationPicker = function() {
    this.each(function () {
        $(this).data('plugin_location_picker', new LocationPicker(this));
    });
};
