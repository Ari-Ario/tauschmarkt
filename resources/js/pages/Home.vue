
<script setup>
import EnterpriseCard from "../components/EnterpriseCard.vue";
import Header from '../components/header/Header.vue';
</script>

<template>
    <Header></Header>
    <div class="menu">
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