<template>
    <div>
        <Map></Map>
    </div>
</template>
  
<script>
import { useCoreStore } from '@/stores/core';
import Map from "@/components/Map.vue";
export default {
    name: 'Dashboard',
    setup() {
        const coreStore = useCoreStore();

        return { coreStore };
    },
    components: {
        Map,
    },
    data() {
        return {
            
        };
    },
    mounted() {

    },
    beforeUnmount() {

    }
};
</script>