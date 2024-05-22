
<script setup>
import EnterpriseCard from "../components/EnterpriseCard.vue";
import Header from '../components/header/Header.vue';
</script>

<template>
    <Header></Header>
    <div class="menu">

    </div>

    <div class="EnterpriseCardContainer">
        <EnterpriseCard />
    </div>
</template>

<style scoped>
    .menu {
        margin-top: 40px;
    }
    .EnterpriseCardContainer{
        display: flex;
        justify-content: center;
        width: 100%;
    }

</style>