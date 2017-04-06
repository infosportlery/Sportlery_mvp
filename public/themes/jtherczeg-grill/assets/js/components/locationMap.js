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

        if (!Array.isArray(this.locations)) {
            this.locations = [this.locations];
        }

        this.map = L.map($el[0]);
        this.markerPopupTmpl = decodeURI($el.find('#marker-popup-tmpl').detach().html());
        this.markerGroup = null;

        this.initTiles();
        this.addMarkers();

        if ($el.closest('.tab-pane').length) {
            const $tabPane = $el.closest('.tab-pane');
            const $tab = $(`[href="#${$tabPane.attr('id')}"]`);
            $tab.on('shown.bs.tab', () => {
                this.map.invalidateSize();
            if (this.markerGroup.getLayers().length > 0) {
                this.map.fitBounds(this.markerGroup.getBounds());
            } else {
                // Dutch border bounds
                this.map.fitBounds([
                    [50.7504, 3.3316],
                    [53.6316, 7.2275]
                ])
            }
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
        const markers = this.locations.map((location) => {
                const marker = L.marker([location.latitude, location.longitude]).addTo(this.map);
        marker.bindPopup(this.renderMarkerPopup(location));
        return marker;
    });

        this.markerGroup = L.featureGroup(markers);
    }

    renderMarkerPopup(location) {
        return this.markerPopupTmpl.replace(/\[\[\s*?(.+?)\s*?\]\]/g, (match, prop) => {
                const propParts = prop.split('.');
        let result = location;
        for (let i = 0; i < propParts.length; i++) {
            if (!result || typeof result !== 'object' || !result.hasOwnProperty(propParts[i])) {
                break;
            }

            result = result[propParts[i]];
        }
        return result;
    });
    }

    /**
     * Center the map around the first available location.
     */
    centerMap() {
        if (this.locations.length) {

            // this.map.setView([this.locations[0].latitude, this.locations[0].longitude], 13);
        }
    }
}