<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/AuthStore';
import axios from 'axios';

const products = ref({});
const store = useAuthStore();
const sellerId = store?.authUser?.id;
const selectedProduct = ref(null);

const fetchProduct = async () => {
    try {
        const response = await axios.get(`/api/product/${sellerId}`);
        products.value = response.data.products;
    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
};

const openEditPopup = async (product) => {
    // Fetch product data for editing
    try {
        const response = await axios.get(`/api/product/${product.id}`);
        selectedProduct.value = response.data;
        // Open the edit popup and allow user to edit the data
        // Implement the popup logic here
    } catch (error) {
        console.error('Failed to fetch Product for editing:', error);
    }
};

const openWatchPopup = async (product) => {
    // Fetch product data for watching
    try {
        const response = await axios.get(`/api/product/${product.id}`);
        selectedProduct.value = response.data;
        // Open the watch popup to display the data
        // Implement the popup logic here
    } catch (error) {
        console.error('Failed to fetch Product for watching:', error);
    }
};

onMounted(() => {
    fetchProduct();
});
</script>

<template>
  <div class="product-list">
    <div v-for="product in products" :key="product.id" class="product-card">
      <img :src="product.image || '../assets/Placeholder-enterprise.png'" alt="Product Image" class="product-image" />
      <div class="product-actions">
        <h3>{{ product.name }}</h3>
        <p>${{ product.price }}</p>
        <button @click="openEditPopup(product)">Edit</button>
        <button @click="openWatchPopup(product)">Watch</button>
      </div>
    </div>
  </div>

  <!-- Fixed toolbar at the bottom -->
  <!-- <div class="fixed-toolbar">
    <button @click="openEditPopup(selectedProduct)">Edit</button>
    <button @click="openWatchPopup(selectedProduct)">Watch</button>
  </div> -->

  <!-- Popup templates (you can customize these) -->
  <div v-if="selectedProduct" class="popup" @click.self="selectedProduct = null">
    <div class="popup-content">
      <h2>{{ selectedProduct.name }}</h2>
      <p>${{ selectedProduct.price }}</p>
      <img :src="selectedProduct.image || '../assets/Placeholder-enterprise.png'" alt="Product Image" />
      <!-- Add edit form or watch content here -->
    </div>
  </div>
</template>


<style scoped>
.product-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}
.product-card {
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 10px;
  width: 200px;
  text-align: center;
}
.product-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 10px;
}
.product-actions {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 10px;
}
.fixed-toolbar {
  position: fixed;
  bottom: 0;
  width: 100%;
  background: #fff;
  border-top: 1px solid #ddd;
  display: flex;
  justify-content: center;
  padding: 10px;
  gap: 10px;
}
.popup {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.popup-content {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  width: 80%;
  max-width: 500px;
  text-align: center;
}
/* Responsive styles */
@media only screen and (max-width: 600px) {
    .product-list{
        width: 90%;
        height: fit-content;
    }

    .product-card {
        width: 100%;
        text-align: left;
    }

    .product-image {
        margin-top: 30px;
        width: 50%;
    }
}
</style>
