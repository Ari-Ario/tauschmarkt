<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/AuthStore';
import axios, { formToJSON } from 'axios';
import { authClient } from "@/services/AuthService";

import { Plus } from '@element-plus/icons-vue';
import Swal from 'sweetalert2';

defineProps({
    products: Array
});
const products = ref([]);
const store = useAuthStore();
const sellerId = store?.authUser?.id;
const selectedProduct = ref(null);
const productImages = ref([]);
const categories = ref([]);
const brands = ref([]);

const fetchProduct = async () => {
    try {
        const response = await axios.get(`/api/product/${sellerId}`);
        products.value = response.data.products;
    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
    try {
        const data = await axios.get(`/api/categories`);
        categories.value = data.data;
        console.log(categories)
    } catch (error) {
        console.error('Failed to fetch Categories:', error);
    }
};

// Upload multiple images
const dialogImageUrl = ref('');

const handleFileChange = (file) => {
    console.log(file);
    productImages.value.push(file);
};

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url;
    dialogVisible.value = true;
};

const handleRemove = (file) => {
    console.log(file);
};

// Product form data
const id = ref(null);
const name = ref('');
const price = ref(null);
const quantity = ref(null);
const description = ref('');
const product_images = ref([]);
const published = ref(false);
const category_id = ref(null);
const inStock = ref(false);

const isAddProduct = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);

const openEditPopup = async (product) => {

    console.log(product);
    id.value = product.id;
    name.value = product.name;
    price.value = product.price;
    quantity.value = product.quantity;
    description.value = product.description;
    category_id.value = product.category_id;
    product_images.value = product.product_images;

    editMode.value = true;
    dialogVisible.value = true;
    // try {
    //     const response = await axios.get(`/api/product/${product.id}`);
    //     selectedProduct.value = response.data;
    // } catch (error) {
    //     console.error('Failed to fetch Product for editing:', error);
    // }
};


// Reset data after added
const resetFormData = () => {
    id.value = '';
    name.value = '';
    price.value = '';
    quantity.value = '';
    description.value = '';
    productImages.value = [];
    dialogImageUrl.value = '';
};

// Delete single product image
const deleteImage = async (pimage, index) => {
    try {
        const response = await axios.delete(`/admin/products/image/${pimage.id}`);
        product_images.value.splice(index, 1);
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            title: response.data.flash.success
        });
    } catch (err) {
        console.log(err);
    }
};

// Update product method
const updateProduct = async () => {
    const formData = new FormData();
    formData.append('name', name.value || "update");
    formData.append('price', price.value || 1);
    formData.append('quantity', quantity.value || 1);
    formData.append('description', description.value || "the last update");
    formData.append('amount', 2);
    formData.append('category_id', category_id.value || 12);
    formData.append('seller_id', sellerId);
    formData.append('_method', 'PUT');

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Log formData values to debug
    for (const [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // console.log('CSRF Token:', csrfToken);
    // for (const value of formData.values()) { console.log(value) }
    try {
        const response = await authClient.post(`products/update/${id.value}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        console.log(response);

        dialogVisible.value = false;
        resetFormData();
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            title: response.data.flash.success,
        });
    } catch (err) {
        console.log(err);
    }
};


// Delete product method
const deleteProduct = (product, index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes, delete!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await axios.delete(`/products/destroy/${product.id}`);
                this.delete(product, index);
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: response.data.flash.success
                });
            } catch (err) {
                console.log(err);
            }
        }
    });
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
      <div class="product-actions">
        <button @click="openEditPopup(product)">Edit</button>
        <button @click="openWatchPopup(product)">Watch</button>
      </div>
    </div>
  </div>

  <!-- <div class="fixed-toolbar">
    <button @click="openEditPopup(selectedProduct)">Edit</button>
    <button @click="openWatchPopup(selectedProduct)">Watch</button>
  </div> -->

  <div v-if="dialogVisible" :title="editMode ? 'Edit product' : 'Add Product'" class="popup" @click.self="selectedProduct">
    <div class="popup-content">
      
      <form @submit.prevent="updateProduct()">
        <div class="form-group">
          <label for="category" class="block-label">Select Category</label>
          <select id="category" v-model="category_id" class="form-select">
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <input v-model="name" type="text" name="floating_title" id="floating_title" class="form-input" placeholder=" " required />
          <label for="floating_title" class="form-label">Name</label>
        </div>
        <div class="form-group">
          <input type="text" name="floating_price" id="floating_price" class="form-input" placeholder=" " required v-model="price" />
          <label for="floating_price" class="form-label">Price</label>
        </div>
        <div class="form-group">
          <input type="number" name="qty" id="floating_qty" class="form-input" placeholder=" " required v-model="quantity" />
          <label for="floating_qty" class="form-label">Quantity</label>
        </div>
        <div class="form-group">
          <label for="message" class="block-label">Description</label>
          <textarea id="message" rows="4" v-model="description" class="form-textarea" placeholder="Leave a comment..."></textarea>
        </div>
        <div class="image-group">
          <el-upload
            v-model:file-list="productImages"
            list-type="picture-card"
            multiple
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove"
            :on-change="handleFileChange"
          >
            <el-icon class="el-icon">
              <Plus style="width: 1em; height: 1em; color: darkgreen;" />
            </el-icon>
          </el-upload>
        </div>
        <div class="image-list">
          <div v-for="(pimage, index) in product_images" :key="pimage.id" class="image-item">
            <img class="image-thumb" :src="`/${pimage.image}`" alt="">
            <span class="delete-icon" @click="deleteImage(pimage, index)">x</span>
          </div>
        </div>
        <button type="submit" class="form-submit">Submit</button>
      </form>
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
.form-group {
  position: relative;
  width: 100%;
  margin-bottom: 1.5rem;
  /* max-height: 40px; */

}
.form-input {
  padding: 10px 0 5px;
  width: 100%;
  border: none;
  border-bottom: 2px solid #d1d5db;
  background: transparent;
  color: #000;
  font-size: 1rem;
}
.form-label {
  position: absolute;
  top: 10px;
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
  width: 100%;
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
.form-submit {
  display: inline-block;
  padding: 10px 20px;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s ease;
}
.form-submit:hover {
  background: #1e40af;
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
