<template>
    <div>
        <div id="map"></div>
        <WeatherWidget />
        <!-- <pre style="position: fixed; left: 0; bottom:0; background-color: orange; z-index: 9999999;width: 300px; height: 500px; font-size: 12px; overflow: scroll;">
            {{ previousStatus }}
        </pre> -->
    </div>
</template>

<script>
import L from 'leaflet';
import "leaflet/dist/leaflet.css";
import echo from "@/plugins/echo";
import WeatherWidget from "@/components/WeatherWidget.vue";

export default {
    name: 'Map',
    data() {
        return {
            map: null,
            defaultColors: {
                color_plane: '#601338',
                color_path: '#601338',
            },
            // showMe: {}, // dev
            markers: {}, // Store markers for each flight
            previousStatus: {}, // Object to store previous status of flights
            isMapLoaded: false, // Flag to track if map is loaded
        };
    },
    computed: {

    },
    components: {
        WeatherWidget,
    },
    mounted() {
        this.initializeMap();

        echo.channel("flights")
            .listen(".flight.updated", (data) => {
                console.log("Event Received:", data.data);
                // this.showMe = data.data;
                this.updateMap(data.data);
            });
    },
    methods: {

        initializeMap() {
            this.map = L.map('map').setView([54.5, -3], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                // attribution: '&copy; OpenStreetMap contributors',
            }).addTo(this.map);

            // Add zoomend event listener
            this.map.on('zoomend', () => {
                this.updateMarkersAfterZoom();
            });
        },

        updateMap(flightData) {
            flightData.forEach(flight => {
                const { id, current_latitude, current_longitude, origin, destination, status } = flight;

                // Ensure current_latitude and current_longitude are valid numbers
                const latitude = parseFloat(current_latitude);
                const longitude = parseFloat(current_longitude);

                if (isNaN(latitude) || isNaN(longitude)) {
                    console.error(`Invalid coordinates for flight ${id}:`, current_latitude, current_longitude);
                    return;
                }

                // Skip toast during first load or if skipToast is explicitly set to true
                if (this.isMapLoaded && this.previousStatus[id] !== status) {
                    // Handle status change to "in_progress"
                    if (status === 'in_progress' && this.previousStatus[id] !== 'in_progress') {
                        this.$showToast(null, `<b>${flight.flight_number}</b> is now in progress`, 'teal', null);
                    }

                    // Handle status change from "arrived" to "in_progress"
                    if (status === 'arrived' && this.previousStatus[id] === 'in_progress') {
                        this.$showToast(null, `<b>${flight.flight_number}</b> has arrived to ${flight.destination}`, 'blue', null);
                    }

                    // Handle status change from "arrived" to "in_progress"
                    if (status === 'completed' && this.previousStatus[id] === 'arrived') {
                        this.$showToast(null, `<b>${flight.flight_number}</b> has landed in ${flight.destination}`, 'blue', null);
                    }
                }

                // Update previous status after processing the current flight
                this.previousStatus[id] = status;

                // Continue with your map update logic as before
                const roundedLatitude = latitude.toFixed(6);
                const roundedLongitude = longitude.toFixed(6);

                if (!this.isValidLatLng(latitude, longitude)) {
                    console.error(`Coordinates out of bounds for flight ${id}:`, latitude, longitude);
                    return;
                }

                // Add or update the marker for the flight
                if (!this.markers[id]) {
                    this.createRoute(flight);

                    const plCo = flight.color_plane ?? this.defaultColors.color_plane;
                    const marker = L.marker([roundedLatitude, roundedLongitude], {
                        icon: L.divIcon({
                            className: 'leaflet-material-icon',
                            html: `<span class="material-icons" style="font-size: 32px; color:${plCo};">airplanemode_active</span>`,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -16]
                        }),
                    }).addTo(this.map);

                    marker.bindPopup(`
                        <b>Flight:</b> ${flight.flight_number}<br>
                        <b>From:</b> ${origin}<br>
                        <b>To:</b> ${destination}<br>
                        <b>Status:</b> ${status}
                    `);
                    this.markers[id] = marker;
                } else {
                    // Update the marker popup if it's already added
                    const marker = this.markers[id];
                    marker.setLatLng([roundedLatitude, roundedLongitude]);

                    marker.setPopupContent(`
                        <b>Flight:</b> ${flight.flight_number}<br>
                        <b>From:</b> ${origin}<br>
                        <b>To:</b> ${destination}<br>
                        <b>Status:</b> ${status}
                    `);
                }
            });
            if (!this.isMapLoaded) {
                this.isMapLoaded = true;
            }
        },

        createRoute(flight) {

            const oLat = flight.origin_latitude;
            const oLon = flight.origin_longitude;
            const dLat = flight.destination_latitude;
            const dLon = flight.destination_longitude;
            const paCo = (flight.color_path) ?? this.defaultColors.color_path;

            if (this.isValidLatLng(oLat, oLon) && this.isValidLatLng(dLat, dLon)) {
                const flightPath = [
                    [oLat, oLon],
                    [dLat, dLon],
                ];
                L.polyline(flightPath, { color: paCo, weight: 3 }).addTo(this.map);
            }

        },

        updateMarkersAfterZoom() {
            // Update markers after zooming
            Object.keys(this.markers).forEach(id => {
                const marker = this.markers[id];
                const currentLatLng = marker.getLatLng();
                marker.setLatLng(currentLatLng);
            });
        },

        isValidLatLng(lat, lng) {
            return lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
        },
    },
    beforeUnmount() {
        echo.leaveChannel("flights");
        console.log("Left flights channel");
    },
};
</script>
<style scoped>
    #map{
        width: calc(100% + 40px);
        height: calc(100% + 20px);
        position: absolute;
        top: -20px;
        left: -20px;
        overflow: hidden;
        z-index: 2;
    }
</style>