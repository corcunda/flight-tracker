<template>
    <div class="search-container">

        <select v-model="searchQuery.origin" @change="updateDestinations">
            <option value="">All Origins</option>
            <option v-for="origin in availableOrigins" :key="origin" :value="origin">{{ origin }}</option>
        </select>
      
        <select v-model="searchQuery.destination">
            <option value="">All Destinations</option>
            <option v-for="destination in filteredDestinations" :key="destination" :value="destination">{{ destination }}</option>
        </select>
      
        <!-- <button @click="search" class="search material-icons">search</button> -->

    </div>
</template>
  
<script>
export default {
    name: 'FlightSearch',
    props: {
        flightsData: { type: Array, required: true }
    },
    data() {
        return {
            searchQuery: {
                origin: '',
                destination: '',
            },
            availableOrigins: [], // List of unique origin values
            filteredDestinations: [], // List of destinations filtered by selected origin
            filteredFlights: [] // List of flights that match the search criteria
        };
    },
    mounted() {
        // Initialize available origins and destinations
        this.initializeSearchData();
    },
    watch: {
        // Watch for changes in searchQuery and re-filter flights
        'searchQuery.origin': 'updateDestinations',
        'searchQuery.destination': 'filterFlights',
    },
    methods: {
        // Initialize available origins and destinations based on the provided flight data
        initializeSearchData() {
            this.availableOrigins = [...new Set(this.flightsData.map(flight => flight.origin))];
            this.filteredDestinations = this.getDestinationsForOrigin(this.searchQuery.origin);
        },

        // Get destinations available for a specific origin
        getDestinationsForOrigin(origin) {
            if (!origin) return [...new Set(this.flightsData.map(flight => flight.destination))];
            return [...new Set(this.flightsData.filter(flight => flight.origin === origin).map(flight => flight.destination))];
        },

        // Update available destinations when the origin is selected
        updateDestinations() {
            this.filteredDestinations = this.getDestinationsForOrigin(this.searchQuery.origin);

            // Reset destination if the selected one is no longer available for the new origin
            if (!this.filteredDestinations.includes(this.searchQuery.destination)) {
                this.searchQuery.destination = '';
            }

            this.filterFlights();
        },

        // Filter flights based on search criteria
        filterFlights() {
            this.filteredFlights = this.flightsData.filter(flight => {
                // const matchesFlightNumber = flight.flight_number.includes(this.searchQuery.flight_number);
                const matchesOrigin = this.searchQuery.origin ? flight.origin === this.searchQuery.origin : true;
                const matchesDestination = this.searchQuery.destination ? flight.destination === this.searchQuery.destination : true;
                return matchesOrigin && matchesDestination;
            });

            this.$emit('filtered-flights', this.filteredFlights);
        },

        search() {
            this.filterFlights();
        },
    }
};
</script>

<style scoped>
.search-container {
    right: 15px;
    top: 85px;
    position: fixed;
    z-index: 2;
    display: flex;
    gap: 10px;
    padding: 10px;
    background-color: #fff;
    box-shadow: 1px 1px 3px 0 rgba(0, 0, 0, .4);
    border-radius: 4px;
}

input, select {
    padding: 5px 10px;
    font-size: 14px;
    border: 1px solid #eee;
    border-radius: 5px;
    outline: none;
}

button {
    padding: 5px 10px;
    font-size: 14px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

@media only screen and (max-width: 768px) {
    .search-container {
        right: 5px;
        gap: 5px;
    }
    input, select {
        font-size: 12px;
    }
}
</style>
  