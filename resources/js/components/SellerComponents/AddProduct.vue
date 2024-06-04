<script setup>
import FooterSeller from '../../components/footer/FooterSeller.vue';
import { storeToRefs } from 'pinia';
import { reactive, ref, onMounted } from 'vue';
import AuthService from "@/services/AuthService";
import { useAuthStore } from '@/stores/AuthStore';
import axios from 'axios';

import {useRouter} from 'vue-router';

const router = useRouter();
const store = storeToRefs(useAuthStore());
const sellerId = store.user.id;
// Define the categories
const categories = ref([]);

// Reactive state for selected categories
const selectedCategories = ref(new Set());

const loadCategories = async () => {
    try {
      
        const response = await axios.get(`api/categories`);
        categories.value = response.data;
        console.log(categories)
    } catch (error) {
        console.error("Error loading enterprises:", error);
    }
};

onMounted(() => {
    axios.defaults.withCredentials = true;
    loadCategories();
});

// Form state
const isFormOpen = ref(false);
const newCategory = ref('');
const newCategoryInput = ref('');
const product = ref({
    CategoryId: null,
    sellerId: sellerId,
    name: '',
    description: '',
    Grams: null,
    unitPrice: null,
    photo: null,
    published: false,
    inStock: false,
    quantity: 1
});
const priceUnit = ref('perGram');
const selectedCategory = ref('');

// Open form
function openForm(category, CategoryId) {
    isFormOpen.value = true;
    selectedCategory.value = category;
    product.value.CategoryId = CategoryId;
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

const resetForm = () => {
      product.value = {
        name: '',
        description: '',
        Grams: null,
        pricePerKilogram: null,
        unitPrice: null,
        photo: null,
      };
      priceUnit.value = 'perGram';
    };

const handleFileUpload = (event) => {
  product.value.photo = event.target.files[0];
};

const handlePriceUnitChange = (event) => {
  priceUnit.value = event.target.value;
};

const saveProduct = async () => {
  try {
    console.log(store.user.id)
    const formData = new FormData();
    formData.append('category_id', product.value.CategoryId); // Ensure this matches your backend
    formData.append('seller_id', store.authUser.value.id);
    formData.append('name', product.value.name);
    formData.append('description', product.value.description);
    formData.append('published', true);
    formData.append('inStock', true);
    formData.append('quantity', 1);


    if (priceUnit.value === 'perGram') {
      formData.append('amount', product.value.Grams);
    } else {
      formData.append('amount', 0); // Default to 1 if not perGram
    }

    formData.append('price', product.value.unitPrice);

    if (product.value.photo) {
      formData.append('product_picture', product.value.photo);
    }
    console.log(product.value);

    const response = await axios.post('/api/product/add', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    console.log('Product added successfully:', response.data);
    selectedCategories.value.add(product.value.name);
    closeForm();
  } catch (error) {
    console.error('Error adding product:', error);
  }
};

</script>

<template>
    <div class="form-container">
        <div class="categories-selector">

            <h3>Welche Kategorien bieten Sie an?</h3>

            <div v-for="category in categories" :key="category.id" class="category-button" :class="{ selected: selectedCategories.has(category) }" @click="openForm(category.name, category.id)">
                {{ category.name }}
            </div>

            <div v-if="isFormOpen" class="form-popup">
              <form @submit.prevent="saveProduct()">
                <input type="hidden" name="" :class="{ selected: selectedCategories.has(category) }" v-model="product.CategoryId">
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
                  <label for="description">Beschreibung</label>
                  <textarea v-model="product.description" required></textarea>
                </div>

                <div class="form-group">
                  <label for="photo">Foto von Ware</label>
                  <input type="file" @change="handleFileUpload" ref="backgroundImageInput" accept="image/*" capture="environment" />
                </div>

                <div class="form-group">
                  <label for="price-unit">Prise Einheit</label>
                  <select v-model="priceUnit" @change="handlePriceUnitChange">
                    <option value="perGram">Pro Gram</option>
                    <option value="perKilogram">Pro Kilogram</option>
                    <option value="perUnit">Pro Stuck</option>
                  </select>
                </div>

                <div class="form-group" v-if="priceUnit === 'perGram'">
                  <label for="price-per-gram">Wieviele Grams</label>
                  <input type="number" v-model.number="product.Grams" required />
                </div>

                <div class="form-group">
                  <label for="unit-price">Prise (Franken)</label>
                  <input type="number" v-model.number="product.unitPrice" required />
                </div>

                <button type="submit" style="margin-bottom: 10px;">Speichern</button>
                <button type="button" @click="closeForm" style="background-color: #e0e0e0; color: #555;">Ablehnen</button>
              </form>
            </div>

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
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  width: 300px;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.category-button {
  display: flex;
  justify-content: center;
  padding: 10px;
  cursor: pointer;
  width: 100%;
  background-color: none;
  border: none;
  border-radius: 10px;
  text-align: center;
  cursor: pointer;
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
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
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
  margin: 0;
  padding: 20px;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
  text-align: center;
  display: block;
  margin-bottom: 8px;
  font-size: 1rem;
  font-weight: 600;
  color: #555;
}

.form-group input,
.form-group textarea,
.form-group select {
  text-align: center;
  width: 92%;
  padding: 5px;
  font-size: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  border-color: #42b983;
  text-align: center;
  width: 100%;
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
    display: flex;
    justify-content: center;
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
