export default class LocationMap {
    /**
     * Create a new map instance in the given jQuery element.
     * Automatically initialized on elements that use a `data-component="locationMap"` attribute.
     *
     * Config:
     * data-locations="{name}": The name of the locations property on the `window.sportlery` object.
     * data-details-url="{url}": The URL to the details page of the location, replace ID with _id_
     *
     * @param {jQuery} $el
     */
    constructor($el) {
        if (!window.L) {
            throw new Error('Leaflet must be included to load the location map.');
        }

        const locationVarName = $el.data('locations') || 'locations';

        if (!window.sportlery || !window.sportlery[locationVarName]) {
            throw new Error('The locations must be available on the window in order to display them.');
        }

        this.locations = window.sportlery[locationVarName];
        this.detailsUrl = $el.data('details-url');
        this.map = L.map($el[0]);
        this.markerPopupTmpl = decodeURI($el.find('#marker-popup-tmpl').html());

        this.centerMap();
        this.initTiles();
        this.addMarkers();

        if ($el.closest('.tab-pane').length) {
            var $tabPane = $el.closest('.tab-pane');
            var $tab = $(`[href="#${$tabPane.attr('id')}"]`);
            $tab.on('shown.bs.tab', () => {
                this.map.invalidateSize();
            })
        }
    }

    /**
     * Initialize the tile layer and add it to the map.
     */
    initTiles() {
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(this.map);
    }

    /**
     * Add a marker to the map for every given location.
     */
    addMarkers() {
        this.locations.forEach((location) => {
            var marker = L.marker([location.latitude, location.longitude]).addTo(this.map);
            marker.bindPopup(this.renderMarkerPopup(location));
        });
    }

    renderMarkerPopup(location) {
        return this.markerPopupTmpl.replace(/\[\[\s*?(.+?)\s*?\]\]/g, function(match, prop) {
            const propParts = prop.split('.');
            let result = location;
            for (let i = 0; i < propParts.length; i++) {
                if (result.hasOwnProperty(propParts[i])) {
                    result = result[propParts[i]];
                } else {
                    break;
                }
            }
            return result;
        });
    }

    /**
     * Center the map around the first available location.
     */
    centerMap() {
        if (this.locations[0]) {
            this.map.setView([this.locations[0].latitude, this.locations[0].longitude], 13);
        } else {
            console.log('No locations');
        }
    }

    /**
     * Build the details url for the given id.
     *
     * @param  {string}  id
     */
    getDetailsUrl(id) {
        return this.detailsUrl.replace('_id_', id);
    }
}
