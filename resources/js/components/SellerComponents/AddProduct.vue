<script setup>
import FooterSeller from '../../components/footer/FooterSeller.vue';
import { storeToRefs } from 'pinia';
import { reactive, ref, onMounted, watch } from 'vue';
import { authClient } from "@/services/AuthService";
import { useAuthStore } from '@/stores/AuthStore';
import axios from 'axios';
import Swal from 'sweetalert2';
import { WebCamUI } from 'vue-camera-lib';
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
    quantity: null,
    unitPrice: null,
    photo: null,
    published: false,
    inStock: false,
    quantity: null,
    mass_unit:''
});
const priceUnit = ref('perGram');
const selectedCategory = ref('');
const setMassUnit = (unit) => {
      product.value.mass_unit = unit;
      return unit;
    };

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
    // console.log(store.user.id)
    const formData = new FormData();
    formData.append('category_id', product.value.CategoryId); // Ensure this matches your backend
    formData.append('seller_id', store.authUser.value.id);
    formData.append('name', product.value.name);
    formData.append('description', product.value.description);
    formData.append('published', true);
    formData.append('inStock', true);
    formData.append('quantity', product.value.quantity);
    formData.append('mass_unit', product.value.mass_unit);

    if (priceUnit.value === 'perGram') {
      formData.append('amount', product.value.Grams);
    } else {
      formData.append('amount', 0); // Default to 1 if not perGram
    }

    formData.append('price', product.value.unitPrice);

    if (product.value.photo) {
      formData.append('product_picture', product.value.photo);
    }
    // console.log(product.value);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const response = await authClient.post('/product/add', formData);

    // console.log('Product added successfully:', response.data);
    selectedCategories.value.add(product.value.name);
    closeForm();
    Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            title: 'Produkt wurde hizugefügt!',
        });
  } catch (error) {
    console.error('Error adding product:', error);
    Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Ups. Fehler, versuchen Sie wieder!'
      });
  }
};

const webCamVisible = ref(false); // Control the visibility of WebCamUI

const showWebCam = () => {
  webCamVisible.value = !webCamVisible.value;
};
const hideWebCam = () => {
  webCamVisible.value = false;
};

const photoTaken = (data) => {
  console.log('image blob: ', data.blob);
  console.log('image data url', data.image_data_url);

  // Create a new File object from the blob to simulate a file upload event
  const file = new File([data.blob], 'photo.jpg', { type: 'image/jpeg' });

  // Create a fake event to pass to handleFileUpload
  const fakeEvent = {
    target: {
      files: [file],
    },
  };

  handleFileUpload(fakeEvent);
  webCamVisible.value = false; // Hide the WebCamUI after taking the photo
};

const triggerFileInput = () => {
    this.$refs.backgroundImageInput.click();
  }
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
                  <label for="name">Produktname</label>
                  <input type="text" v-model="product.name"  class="form-input" required />
                </div>

                <div class="form-group">
                  <label for="description">Beschreibung</label>
                  <textarea v-model="product.description"  class="form-input" required></textarea>
                </div>

                <div class="form-group" style="display: flex;">
                  <label for="photo">Foto von Ware</label>
                  <input type="file" @change="handleFileUpload" ref="backgroundImageInput"  class="form-input" accept="image/*" capture="environment" />
                  
                  <!-- <div class="form-input file-input-container" @click="triggerFileInput">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
                      <path d="M160-80v-80h640v80H160Zm200-160v-280H200l280-360 280 360H600v280H360Zm80-80h80v-280h76L480-750 364-600h76v280Zm40-280Z"/>
                    </svg>
                  </div> -->

                  <div class="camera-icon bg-camera cam" @click="showWebCam" style="cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
                      <path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/>
                    </svg>
                  </div>
                </div>
                <WebCamUI
                  v-if="webCamVisible"
                  :fullscreenState="false"
                  @photoTaken="photoTaken"
                  ref="webCam"
                />

                <div class="form-group">
                  <label for="price-unit">Prise Einheit</label>
                  <select v-model="priceUnit" class="form-select" @change="handlePriceUnitChange">
                    <option value="perKilogram">Pro Kilogram</option>
                    <option value="perGram">Pro Gram</option>
                    <option value="perUnit">Pro Stuck</option>
                  </select>
                </div>

                <div class="form-group" v-if="priceUnit === 'perGram'">
                  <label for="price-per-gram">Wieviele Grams</label>
                  <input type="number" v-model.number="product.Grams"  class="form-input" required />
                </div>
                <div class="form-group" v-if="priceUnit === 'perKilogram'">
                  <label for="unit-price">Quantität (in Kilograms)</label>
                  <input type="number" name="qty" id="floating_qty" v-model.number="product.quantity" class="form-input" required />
                  <input type="hidden" :value="setMassUnit('KG')" />
                </div>
                <div class="form-group" v-else-if="priceUnit === 'perUnit'">
                  <label for="unit-price">Quantität (in Stuck)</label>
                  <input type="number" name="qty" id="floating_qty" v-model.number="product.quantity" class="form-input" required />
                  <input type="hidden" :value="setMassUnit('Stuck')" />
                </div>

                <div class="form-group" v-else>
                  <label for="unit-price">Quantität (zahl der Einheiten)</label>
                  <input type="number" name="qty" id="floating_qty" v-model.number="product.quantity" class="form-input" required />
                  <input type="hidden" :value="setMassUnit('Grams')" />
                </div>

                <div class="form-group">
                  <label for="unit-price">Preis (Franken)</label>
                  <input type="number" v-model="product.unitPrice" class="form-input" step="0.01" required />
                </div>
                <div class="form-group" style="display: flex; justify-content: space-between;">
                  <button type="submit">Speichern</button>
                  <button type="button" @click="closeForm" style="background-color: #e0e0e0; color: #555;">Ablehnen</button>
                </div>
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

.product-form-page {
    padding: 20px;
  }
  
  .form-popup {
    position: absolute;
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
.form-group {
  position: relative;
  width: 100%;
  margin-bottom: 1.5rem;
  /* max-height: 40px; */

}
.form-input {
  padding: 5px 0 5px;
  width: 100%;
  border: none;
  border-bottom: 2px solid #d1d5db;
  background: transparent;
  color: #000;
  font-size: 1rem;
}
.form-label {
  position: absolute;
  top: 5px;
  left: 0;
  color: #6b7280;
  font-size: 1rem;
  pointer-events: none;
  transition: all 0.2s ease;
}
.form-input:focus {
  border-bottom: 2px solid #2563eb;
  outline: none;
}
.form-input:focus + .form-label,
.form-input:not(:placeholder-shown) + .form-label {
  top: -20px;
  font-size: 0.75rem;
  color: #2563eb;
}
.form-group select:focus {
  border-color: #42b983;
  text-align: center;
  width: 100%;
}
.form-select {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  font-size: 1rem;
  color: #000;
}
.form-textarea {
  width: 94%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  font-size: 1rem;
  color: #000;
}
.image-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.image-group {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 80px;
  height: 80px;
  border: 2px dashed #d1d5db;
  border-radius: 5px;
}
.el-icon {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  font-size: 24px;
  color: #2563eb;
}

.plus-icon {
  font-size: 24px;
  color: #2563eb;
}
.image-item {
  position: relative;
  width: 80px;
  height: 80px;
}
.image-thumb {
  width: 100%;
  height: 100%;
  border-radius: 5px;
}
.delete-icon {
  position: absolute;
  top: 5px;
  right: 5px;
  width: 20px;
  height: 20px;
  background: red;
  color: #fff;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
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
  
  .form-popup {
    position: absolute;
    width: 90%;
    height: 100%;
    }
}

@media (max-width: 1025px) {

.cam {
  display: none;
}
}
</style>
