<template>
    <div v-if="Object.keys(city).length > 0" class="weather-widget">
        <transition 
            name="fade-up" 
            @before-enter="beforeEnter" 
            @enter="enter" 
            @leave="leave">
            <div :key="city.name" class="city-item">
                <div class="city-info">
                    <span class="city-name">{{ city?.name }}</span>
                    <span class="city-time">{{ city?.local_time }}</span>
                    <span class="city-temp">{{ city?.temperature }}°C</span>
                </div>
                <span v-if="city.icon" class="city-temp-icon"><img :src="city.icon" /></span>
            </div>
        </transition>
    </div>
</template>

<script>
import { useCoreStore } from '@/stores/core';

export default {
    name: 'WeatherWidget',
    setup() {
        const coreStore = useCoreStore();
        let intervalId = null; // Store the interval ID

        return { coreStore, intervalId };
    },
    data() {
        return {
            city: {},
            cities: (this.coreStore.config.weather_cities) ?? [],
            currentCityIndex: 0, // Index to track the current city
        };
    },
    mounted() {
        this.init();
    },
    beforeUnmount() {
        if (this.intervalId) {
            clearInterval(this.intervalId); // Clear the interval
            console.log('Weather widget interval cleared');
        }
    },
    methods: {
        // Shuffle the cities array
        randomizeCities() {
            this.cities = this.cities.sort(() => Math.random() - 0.5);
        },
        init() {
            if( this.cities.length ) {
                this.randomizeCities();
                this.fetchNextCityWeather();
                this.intervalId = setInterval(this.fetchNextCityWeather, 10000);
            }
        },
        fetchNextCityWeather() {
            let vm = this;
            const currentCity = vm.cities[vm.currentCityIndex];
            // vm.coreStore.isLoading = true;
            vm.coreStore.fetchWeather(currentCity)
                .then(response => {
                    vm.city = response.data;
                    // Update the current city index, looping back to 0 when we reach the end of the list
                    vm.currentCityIndex = (vm.currentCityIndex + 1) % vm.cities.length;
                })
                .catch(error => {
                    console.log('Error fetching weather:', error);
                    clearInterval(this.intervalId);
                    this.$showToast('Error', `Problem fetching the weather`, 'error', 'times');
                })
                .finally(() => {
                    // vm.coreStore.isLoading = false;
                });
        },

        // Animations
        beforeEnter(el) {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
        },
        enter(el, done) {
            el.offsetHeight; // trigger reflow to restart animation
            el.style.transition = 'opacity 1s, transform 1s';
            el.style.opacity = 1;
            el.style.transform = 'translateY(0)';
            done();
        },
        leave(el, done) {
            el.style.transition = 'opacity 1s, transform 1s';
            el.style.opacity = 0;
            el.style.transform = 'translateY(-20px)';
            done();
        },
    },
};
</script>
  
<style scoped>
.weather-widget {
    position: fixed;
    bottom: 10px;
    left: 10px;
    z-index: 9;
    font-weight: 300;
}
.city-item{
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    box-shadow: 0 0 4px 0 rgba(0, 0, 0, .4);
    padding: 5px 10px;
    border-radius: 4px;
}
.city-info{
    display: flex;
    gap: 8px;
    align-items: center;
}
.city-name{
    font-weight: 400;
}
.city-time{
    font-size: 14px;
}
.city-temp{
    font-size: 14px;
}
.city-temp-icon{
    position: absolute;
    top: -25px;
    /* left: 50%; */
    /* transform: translate(-50%); */
    top: -20px;
    right: -20px;
}
img{
    max-width: 40px;
}
</style>