<script setup>
    import { useAuthStore } from '../stores/AuthStore';
    import LogoutButton from '@/components/LogoutButton.vue';
    // import MapComponent from '@/components/MapComponent.vue';

    const store = useAuthStore();

</script>


<template>
    <header>
        <LogoutButton/>
    </header>
    <span>Dashboard</span>
    <h1>Hallo {{ store.authUser.firstname }} {{ store.authUser.lastname }} {{ store.authUser.is_seller }}</h1>

</template>