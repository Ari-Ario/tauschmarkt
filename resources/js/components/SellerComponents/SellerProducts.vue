<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/AuthStore';
import axios from 'axios';

const products = ref([
  { id: 1, name: 'Product 1', price: 100, image: '' },
  { id: 2, name: 'Product 2', price: 200, image: 'path/to/product2.jpg' },
  { id: 3, name: 'Product 2', price: 200, image: 'path/to/product2.jpg' },
  { id: 4, name: 'Product 2', price: 200, image: 'path/to/product2.jpg' },

  // Add more products as needed
]);
const store = useAuthStore();
const sellerId = store?.authUser?.id;

const fetchProduct = async () => {
    try {
        const response = await axios.get(`/api/product/${sellerId}`);
        products.value = await response.data;
        console.log(products)
    } catch (error) {
        console.error('Failed to fetch Product:', error);
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
      <h3>{{ product.name }}</h3>
      <p>${{ product.price }}</p>
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
