<script setup>
import FooterSeller from '../../components/footer/FooterSeller.vue';

import { reactive, ref } from 'vue';
import { useAuthStore } from '@/stores/AuthStore';
import AuthService from "@/services/AuthService";

import {useRouter} from 'vue-router';

const router = useRouter();

// Define the categories
const categories = [
  'Honigprodukte',
  'Obst & Gemüse',
  'Milchprodukte',
  'Vollkornprodukte',
  'Getränke und Wein',
  'Fleisch & Geflügel',
  'Blumen & Gärtnerei'
];

// Reactive state for selected categories
const selectedCategories = ref(new Set());

// Toggle category selection
function toggleCategory(category) {
  if (selectedCategories.value.has(category)) {
    selectedCategories.value.delete(category);
  } else {
    selectedCategories.value.add(category);
  }
}

// Save selected values
// function saveSelections() {
//   const selectedArray = Array.from(selectedCategories.value);
//   sessionStorage.setItem('myArray', selectedArray)

//   router.push({ name: 'addProduct', params: { data: JSON.stringify(selectedArray.value) } });
// }

// Form state
const isFormOpen = ref(false);
const newCategory = ref('');
const newCategoryInput = ref('');
const product = ref({
    name: '',
    description: '',
    pricePerGram: null,
    pricePerKilogram: null,
    unitPrice: null,
    photo: null,
});
const priceUnit = ref('perGram');
const selectedCategory = ref('');

// Open form
function openForm(category) {
    isFormOpen.value = true;
    selectedCategory.value = category;
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

const handleFileUpload = (event) => {
      const file = event.target.files[0];
      product.value.photo = file;
    };

const handlePriceUnitChange = () => {
      product.value.pricePerGram = null;
      product.value.pricePerKilogram = null;
    };

const resetForm = () => {
      product.value = {
        name: '',
        description: '',
        pricePerGram: null,
        pricePerKilogram: null,
        unitPrice: null,
        photo: null,
      };
      priceUnit.value = 'perGram';
    };

const submitForm = async () => {
    const formData = new FormData();
    formData.append('name', product.value.name);
    formData.append('description', product.value.description);
    formData.append('pricePerGram', product.value.pricePerGram);
    formData.append('pricePerKilogram', product.value.pricePerKilogram);
    formData.append('unitPrice', product.value.unitPrice);
    formData.append('photo', product.value.photo);

    try {
    const response = await axios.post('/api/products', formData, {
        headers: {
        'Content-Type': 'multipart/form-data',
        },
    });
    console.log('Product added successfully:', response.data);
    selectedCategories.value.push(product.value.name);
    closeForm();
    } catch (error) {
    console.error('Error adding product:', error);
    }
};

const saveNewCategory= ()=> {
    const newCategory = document.getElementById("newCategory").value;
    // if (newCategory.value && !categories.value.includes(newCategory.value)) {
    //     categories.value.push(newCategoryInput.value);
    // }
    // add the ctegory in database
    openForm(newCategory)
    newCategory.value = '';
      
}

</script>

<template>
    <div class="form-container">
        <div class="categories-selector">
            <div class="back">
                <router-link to="/dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
                </router-link>
            </div>
            <h3>Welche Kategorien bieten Sie an?</h3>

            <div v-for="category in categories" :key="category" class="category-button" :class="{ selected: selectedCategories.has(category) }" @click="openForm(category)">
                {{ category }}
            </div>
            <form class="" >
                <fieldset style="display: flex;">
                <legend for="">Oder neue Kategory</legend>
                        <input type="text" name="newCategory" id="newCategory" required />                        
                        <button type="button" @click="saveNewCategory()">Hinzufügen</button>
                </fieldset>
            </form>

            <!-- <div v-for="category in categories" :key="category" class="category-button" :class="{ selected: selectedCategories.has(category) }" @click="toggleCategory(category)">
            {{ category }}
            </div> -->

            <div v-if="isFormOpen" class="form-popup">
                <form @submit.prevent="saveProduct">

                    <div class="form-group">
                        <div class="category-button" :class="{ selected: selectedCategories.has(category) }" style="width: 90%;">
                        {{ selectedCategory }}
                        </div>                    
                    </div>

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
                        <input type="file" @change="handleFileUpload"  ref="backgroundImageInput" />
                    </div>
                    <div class="form-group">
                        <label for="price-unit">Price Unit</label>
                        <select v-model="priceUnit" @change="handlePriceUnitChange">
                        <option value="perGram">Per Gram</option>
                        <option value="perKilogram">Per Kilogram</option>
                        <option value="perUnit">Per unit</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="priceUnit === 'perGram'">
                        <label for="price-per-gram">Price (per gram)</label>
                        <input type="number" v-model.number="product.pricePerGram" required />
                    </div>
                    <div class="form-group" v-if="priceUnit === 'perKilogram'">
                        <label for="price-per-kilogram">Price (per kilogram)</label>
                        <input type="number" v-model.number="product.pricePerKilogram" required />
                    </div>
                    <div class="form-group">
                        <label for="unit-price">Unit Price</label>
                        <input type="number" v-model.number="product.unitPrice" required />
                    </div>
                    <button type="submit" style="margin-bottom: 10px;">Save</button>
                    <button type="button" @click="closeForm" style="background-color: #e0e0e0; color: #555;">Cancel</button>
                </form>
            </div>

            <!-- <button class="submit-button" @click="saveSelections">
                Weiter
            </button> -->
        </div>
      <div v-show="currentPage !== 'Map'">
          <FooterSeller />
      </div>
    </div>
</template>

<style scoped>

.back {
    width: 100%;
    text-align: left;
    margin: 0;
}

.form-container {
display: flex;
justify-content: center;
align-items: center;
}
.categories-selector {
    display: flex;
    text-align: center;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 8px;
    width: 300px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.category-button {
  background-color: none;
  border: none;
  border-radius: 10px;
  padding: 15px;
  margin: 5px 0;
  width: 80%;
  text-align: center;
  cursor: pointer;
  font-size: 16px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

}

.category-button.selected {
  background-color: #87e887;
}

legend {
    padding: 0;
    margin: 0;
    border-radius: 10px;
}

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
  
  
  .add-category {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.add-category h1 {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 20px;
  color: #333;
}

.selected-categories {
  margin-bottom: 20px;
}

.categories-list {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}

.add-button {
  padding: 10px 20px;
  font-size: 1rem;
  color: white;
  background-color: #42b983;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-right: 10px;
}

.add-button:hover {
  background-color: #38a169;
}

.categories-list ul {
  display: flex;
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.categories-list li {
  margin-right: 10px;
  padding: 5px 10px;
  background-color: #e0e0e0;
  border-radius: 4px;
}

form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-size: 1rem;
  font-weight: 600;
  color: #555;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 90%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  border-color: #42b983;
}

form button {
  padding: 12px 20px;
  font-size: 1rem;
  color: white;
  background-color: #42b983;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-right: 10px;
}

form button:hover {
  background-color: #38a169;
}

.cancel-button {
  padding: 12px 20px;
  font-size: 1rem;
  color: #555;
  background-color: #e0e0e0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cancel-button:hover {
  background-color: #d0d0d0;
}

@media (max-width: 600px) {

    .categories-selector {
        width: 100%;
        padding: 20px;
    }

  .category-button{
    width: 90%;
    font-size: 14px;
  }
  
  .submit-button {
    width: 100%;
    font-size: 14px;
  }
  .form-popup {
    position: absolute;
    width: 90%;
    height: 100%;
    }
}
</style>
