<template>
    <div>
        <h1>Dashboard</h1>
        <!-- <router-link to="/">Go to Main Page</router-link> -->
        <!-- PARA O WEBSOCKJET TEST -->
        <div v-if="eventData">
            <p>Flight: {{ eventData.flight }}</p>
            <p>Status: {{ eventData.status }}</p>
        </div>
    </div>
</template>
  
<script>
import { useCoreStore } from '@/stores/core';
import echo from "@/plugins/echo";
export default {
    name: 'Dashboard',
    setup() {
        const coreStore = useCoreStore();

        return { coreStore };
    },
    data() {
        return {
            eventData: null,
        };
    },
    mounted() {
        echo
            .channel("flights")
            .listen(".flight.updated", (data) => {
                console.log("Event Received:", data);
                this.eventData = data.data; // Update component state with event data
                // Handle the event
            });
    },
    beforeUnmount() {
        echo.leaveChannel("flights");
        console.log("Left flights channel");
    }
};
</script>