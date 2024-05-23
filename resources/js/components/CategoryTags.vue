<script setup>
    import axios from 'axios'; 
    import { ref , onMounted, onBeforeMount } from 'vue';
    import {useRoute} from 'vue-router';
    import {authClient} from '@/services/AuthService';

    const products = ref(null);

    const loadproducts = async () => {
    try {
        const response = await axios.get('api/products'); 
        products.value = await response.data;
        console.log(response.data);
    } catch (error) {
        console.error('Fehler beim Laden der Seite:', error);
    }
}
    onBeforeMount(() => {
        loadproducts();
        });
    
    
</script>

<template>
    <div class="tag-container">

        <router-link v-for="product in products" :key="product.id" :to="{ name: 'allSeites', params: { tag: product.name.toLowerCase()  } }" customv-slot="{ navigate }">
            <button class="tag">{{ product.tag }}</button>
        </router-link>
        </div>
</template>

<style scoped>


.tag {
    margin: 10px 10px 0 0;
    font-size: 18px;
    font-weight: 600;
    border: 2px solid rgb(231, 231, 231); 
    border-radius: 1.2rem;
    background-color: transparent;
    color: rgb(231, 231, 231);
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    gap: 10px;
}

.tag:hover{
    background-color: rgba(120, 118, 118, 0.3);
}
</style>