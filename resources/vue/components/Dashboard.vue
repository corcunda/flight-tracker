<template>
    <div>
        <Map></Map>
        <div v-if="!coreStore.simulationStarted" id="simulation-start-message">
            <div class="background"></div>
            <div class="message">
                <span class="close material-icons" @click="close">close</span>
                <h2>Welcome</h2>
                <p>This is a simulation designed to showcase flight tracking in action. You'll have the opportunity to explore flight routes, real-time updates, and other features—all in a simulated environment.</p>
                <p>Starting the simulation will reset the existing data related to flight routes. This means any current flight route information, including data visible to other logged-in users, will be reset. Please keep this in mind before proceeding, as it will affect ongoing simulations for other users.</p>
                <p>Enjoy exploring the simulation!</p>
                <button type="button" @click="initializeSimulation">Start Simulation</button>
                <button type="button" class="logout" @click="logout">Logout</button>
            </div>
        </div>
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
    methods: {
        logout(){
            this.$root.$refs['Header'].logout();
        },
        initializeSimulation(){
            let vm = this;
            vm.coreStore.isLoading = true;
            vm.coreStore.simulateStart()
                .then(response => {
                    setTimeout(()=>{
                        vm.coreStore.simulationStarted = true;
                    }, 2000);
                })
                .catch(error => {
                    console.log('Error initialize simulation:', error);
                    this.$showToast('Error', `Could not initialize the simulation`, 'error', 'times');
                })
                .finally(() => {
                    setTimeout(()=>{
                        vm.coreStore.isLoading = false;
                    }, 2000);
                });
        },
        close() {
            document.getElementById('simulation-start-message').remove();
        },
    },
};
</script>
<style scoped>
#simulation-start-message .background{
    position: fixed;
    z-index: 10;
    width: 100vw;
    height: 100vh;
    top: 0;
    left: 0;
    background: rgba(0,0,0,.6);
}
#simulation-start-message .message{
    position: absolute;
    z-index: 11;
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    max-width: 550px;
    min-width: 340px;
    top: calc(50% - 80px);
    top: 50%;
    left: 50%;
    box-shadow: 1px 1px 3px 0 rgba(0, 0, 0, .4);
    transform: translate(-50%, -50%);
}
#simulation-start-message .message .close{
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
#simulation-start-message h2{
    margin-top: 0;
}
#simulation-start-message p{
    position: relative;
}
#simulation-start-message button{
    position: relative;
}
button.logout{
    background: none;
    color: #00b5ad;
    width: auto;
    padding: 0;
    margin: 0 auto;
    display: flex;
    margin-top: 10px;
    font-weight: 400;
    font-size: 14px;
}

@media only screen and (max-width: 768px) {
    #simulation-start-message h2{
        font-size: 20px;
    }
    #simulation-start-message p{
        font-size: 14px;
    }
}
</style>