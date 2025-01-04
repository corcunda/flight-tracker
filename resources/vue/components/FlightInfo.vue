<template>
    <div v-if="flight" class="flight-info">
        <div class="flight-info-wrapper">
            <span class="close material-icons" @click="close">close</span>
            <h3>Flight Information</h3>
            <p><strong>Airline:</strong> {{ flight.airline }} <span>({{ flight.flight_number }})</span></p>
            <p><strong>Origin:</strong> {{ flight.origin }}</p>
            <p><strong>Destination:</strong> {{ flight.destination }}</p>
            <p><strong>Current Location:</strong> {{ formattedLocation }}</p>
            <p><strong>Status:</strong> {{ flight.status }}</p>
        </div>
    </div>
</template>
  
<script>
export default {
    name: "FlightInfo",
    props: {
        flight: { type: Object, default: null, },
    },
    computed: {
        formattedLocation() {
            const lat = parseFloat(this.flight.current_latitude || 0).toFixed(5);
            const lng = parseFloat(this.flight.current_longitude || 0).toFixed(5);
            return `${lat}, ${lng}`;
        },
    },
    methods: {
        close() {
            this.$parent.selectedFlight = null;
        },
    },
};
</script>

<style scoped>
.flight-info {
    position: fixed;
    z-index: 10;
    bottom: 10px;
    right: 10px;
    transition: all .3s ease;
}
.flight-info-wrapper{
    position: relative;
    padding: 30px;
    border-radius: 8px;
    background-color: #fff;
    font-size: 14px;
    box-shadow: 1px 1px 3px 0 rgba(0, 0, 0, .4);
}
.flight-info .close{
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
.flight-info h3{
    margin-top: 0;
}
.flight-info p{
    margin: 0;
}
.flight-info p span{
    font-style: italic;
}

@media only screen and (max-width: 768px) {
    .flight-info{
        bottom: 0;
        right: 5px;
        width: calc(100% - 10px);
    }

}
</style>
  