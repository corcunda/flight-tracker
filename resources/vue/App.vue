<template>
    <div class="main-wrapper">
        <RouterView />
    </div>
</template>

<script>
import {RouterView} from "vue-router";
export default {
    name: 'App',
    components: {
        RouterView
    },
};
</script>

<style>
body{
    background: #333;
}
</style>
<style scoped>
h1 {
    color: #42b983;
}
</style>