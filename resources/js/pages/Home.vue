
<script setup>
import EnterpriseCard from "../components/EnterpriseCard.vue";
// import Heroheader from '../components/Heroheader.vue';
</script>

<template>
    <div>
        <h1>Startseite</h1>
        <router-link to="/login">Link zur Login Seite</router-link>
        <br>
        <router-link to="/register">Link zur RegisterUser Seite</router-link>
        <br>
        <router-link to="/registerSeller">Link zur RegisterSeller Seite</router-link>
        <br>

        <router-link to="/map">Map</router-link>
    </div>

    <div class="EnterpriseCardContainer">
        <EnterpriseCard />
    </div>
</template>

<style scoped>

    .EnterpriseCardContainer{
        display: flex;
        justify-content: center;
        width: 100%;
    }

</style>