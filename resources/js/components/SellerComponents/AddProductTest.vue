<script setup>
import { ref, onMounted, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router';

// Receive selected categories from the route
const route = useRoute();
let selectedCategories = ref([]);
let receivedData = ref([]);
onBeforeMount(() => {
    selectedCategories = sessionStorage.getItem("myArray");
    receivedData = selectedCategories.split(",");
    console.log(selectedCategories)
  if (route.params.data) {
    receivedData.value = JSON.parse(route.params.data);
  }
});

// Form state
const isFormOpen = ref(false);
const product = ref({
  name: '',
  description: '',
  photo: null,
  pricePerGram: 0,
  pricePerKilogram: 0,
  unitPrice: 0,
});

// Open form
function openForm() {
  isFormOpen.value = true;
}

// Close form
function closeForm() {
  isFormOpen.value = false;
  resetForm();
}

// Handle file input change
function onFileChange(event) {
  const file = event.target.files[0];
  product.value.photo = file;
}

// Save product details
function saveProduct() {
  console.log('Product saved:', product.value);
  // Implement saving logic here (e.g., send to backend)
  closeForm();
}

// Reset form fields
function resetForm() {
  product.value = {
    name: '',
    description: '',
    photo: null,
    pricePerGram: 0,
    pricePerKilogram: 0,
    unitPrice: 0,
  };
}
</script>


<template>
    <div class="product-form-page">

      <h2 v-for="category in receivedData"> {{ category }}</h2>

      <button @click="openForm">Add New Product</button>
  
      <div v-if="isFormOpen" class="form-popup">
        <form @submit.prevent="saveProduct">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" v-model="product.name" required />
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea v-model="product.description" required></textarea>
          </div>
          <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" @change="onFileChange" />
          </div>
          <div class="form-group">
            <label for="price">Price (per gram)</label>
            <input type="number" v-model.number="product.pricePerGram" required />
          </div>
          <div class="form-group">
            <label for="price">Price (per kilogram)</label>
            <input type="number" v-model.number="product.pricePerKilogram" required />
          </div>
          <div class="form-group">
            <label for="price">Unit Price</label>
            <input type="number" v-model.number="product.unitPrice" required />
          </div>
          <button type="submit">Save</button>
          <button type="button" @click="closeForm">Cancel</button>
        </form>
      </div>
    </div>
  </template>
  
  <style scoped>
  .product-form-page {
    padding: 20px;
  }
  
  .form-popup {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -20%);
    background-color: white;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  input[type="text"],
  input[type="number"],
  textarea {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  
  button {
    margin-right: 10px;
  }
  </style>
  