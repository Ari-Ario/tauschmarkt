<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../../stores/AuthStore';
import axios, { formToJSON } from 'axios';
import { authClient } from "@/services/AuthService";
import  { UploadFile } from 'element-plus';
import { Plus, Delete, Download, ZoomIn  } from '@element-plus/icons-vue';
import Swal from 'sweetalert2';
import { defineComponent } from 'vue'
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'


const products = ref([]);
// const sellerId = store?.authUser?.id;
const selectedProduct = ref(null);
const productImages = ref([]);
const categories = ref([]);

// Fetch user profile data on mount
const store = storeToRefs(useAuthStore());
const sellerId = store?.authUser?.value.id;

const fetchProduct = async () => {
  // console.log(sellerId);

    try {
        const response = await axios.get(`/api/product/${sellerId}`);
        categories.value = response.data.categories;
        products.value = response.data.products.data;
        // console.log(products.value)
        // console.log(categories.value)

    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
};

// Upload multiple images
const dialogVisible = ref(false);
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

const editMode = ref(false);

const openEditPopup = async (product, index) => {

    // console.log(product, index);
    id.value = product.id;
    name.value = product.name;
    price.value = product.price;
    quantity.value = product.quantity;
    description.value = product.description;
    category_id.value = product.category_id;
    product_images.value = product.product_images;

    editMode.value = true;
    dialogVisible.value = true;

};
const closeEditPopup = () => {
    dialogVisible.value = false;
    editMode.value = false;
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

const imagesMode = ref(false);

const openImagesPopup = async (product) => {
  product_images.value = product.product_images;
  // console.log(productImages)
  imagesMode.value = true;

  };
const closeImagesPopup = () => {
    imagesMode.value = false;
};

// Delete single product image
const deleteImage = async (pimage, index) => {
    try {
        const response = await authClient.delete(`/products/image/${pimage.id}`)
          product_images.value.splice(index, 1);
          Swal.fire({
              toast: true,
              icon: 'success',
              position: 'top-end',
              showConfirmButton: false,
              title: 'Bild wurde gelöscht!'
          });
    } catch (err) {
        console.log(err);
        Swal.fire({
            toast: true,
            icon: 'error',
            position: 'top-end',
            showConfirmButton: false,
            title: 'Bild konnte nicht gelöscht werden!'
    });
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
        const response = await authClient.post(`products/update/${id.value}`, formData);
        // console.log(response);
        dialogVisible.value = false;
        resetFormData();
        fetchProduct();
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            title: 'Produkt aktualisiert!',
            timer: 3000
        });
    } catch (err) {
        console.log(err);
        Swal.fire({
            toast: true,
            icon: 'error',
            position: 'top-end',
            showConfirmButton: false,
            title: 'Produkt konnte nicht aktualiziert werden!',
            timer: 3000
    });
  }
};

const deleteProduct = (product) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: 'Sind Sie sicher?',
        text: 'Diese Aktion kann nicht rückgängig gemacht werden!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Nein',
        confirmButtonText: 'Ja, Löschen!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await authClient.delete(`products/destroy/${product.id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                // Handle success response
                products.value.splice(products.value.id, 1);

                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: 'Produkt wurde gelöscht',
                    timer: 3000
                });
            } catch (err) {
                console.log(err);
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: 'Produkt konnte nicht gelöscht werden',
                    timer: 3000
                });
            }
        }
    });
};

function getProductImage(product) {
  if (product.product_images && product.product_images.length > 0) {
    return `/${product.product_images[0].image}`;
  } else {
    return '../assets/Placeholder-enterprise.png';
  }
}


onMounted(() => {
    fetchProduct();
});
</script>

<template>
  <div class="product-list">
    <div v-for="product in products" :key="product.id" class="product-card">
      <div class="product-info" v-if="product.quantity > 1">
        <p>{{ product.name }}</p>
        <p>Quantität: {{ product.quantity }}</p>
      </div>
      <div class="product-info" v-else-if="!product.product_images.length" style="background-color: red; opacity: 0.7;">
        <h3>Fügen Sie Bilder hinzu, damit der Product den Kunden sichtbar wird!</h3>
      </div>
      <div class="product-info" v-else style="background-color: red; opacity: 0.7;">
        <h3>Aktualisieren Sie den Produkt! (Quantität: {{ product.quantity }})</h3>
      </div>

      <img :src="getProductImage(product)" alt="Product Image" class="product-image" />
      <div class="product-actions">
        <button class="product-buttons" @click="openEditPopup(product)">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
        </button>
        <button class="product-buttons" @click="openImagesPopup(product)">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>        </button>
        <button @click="deleteProduct(product)">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
        </button>
      </div>

    </div>
  </div>

  <div v-if="dialogVisible" :title="editMode ? 'Edit product' : 'Add Product'" class="popup" @click.self="selectedProduct">
    <div class="popup-content">
      
      <form @submit.prevent="updateProduct()">
        <div class="form-group">
          <label for="category" class="block-label">Kategorie wählen</label>
          <select id="category" v-model="category_id" class="form-select">
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <input v-model="name" type="text" name="floating_title" id="floating_title" class="form-input" placeholder=" " required />
          <label for="floating_title" class="form-label">Produktname</label>
        </div>
        <div class="form-group">
          <input type="text" name="floating_price" id="floating_price" class="form-input" placeholder=" " required v-model="price" />
          <label for="floating_price" class="form-label">Preis</label>
        </div>
        <div class="form-group">
          <input type="number" name="qty" id="floating_qty" class="form-input" placeholder=" " required v-model="quantity" />
          <label for="floating_qty" class="form-label">Quantität</label>
        </div>
        <div class="form-group">
          <label for="message" class="block-label">Beschreibung</label>
          <textarea id="message" rows="4" v-model="description" class="form-textarea" placeholder="Leave a comment..."></textarea>
        </div>

        <div class="upload-image-group" >
          <div class="relative z-0 w-full mb-6 group" style="display: relative;">
              <el-upload v-model:file-list="productImages" list-type="picture-card" class="avatar-uploader el-upload " multiple
                  :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :on-change="handleFileChange">
                  <el-icon class="avatar-uploader-icon">
                      <Plus />
                  </el-icon>
              </el-upload>

          </div>

          <!-- list of images for selected product -->
          <div class="flex flex-nowrap mb-8 image-group">
            <div v-for="(pimage, index) in product_images" :key="pimage.id" class="single-image">
              <img class="img-thumbnail" :src="`/${pimage.image}`" alt="">
              <span class="delete-icon" @click="deleteImage(pimage, index)">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
              </span>
            </div>
          </div>

        </div>

        <button type="submit" style="margin-bottom: 10px;">Speichern</button>
        <button type="button" @click="closeEditPopup()" style="background-color: #e0e0e0; color: #555;">Abbrechen</button>
      </form>
    </div>
  </div>


  <div v-if="imagesMode" class="popupImages" @click.self="closeImagesPopup()">
    <div class="broadcast">
      <Carousel>
        <Slide v-for="(pimage, index) in product_images" :key="pimage.id">
          <div class="carousel__item">
            <img :src="`/${pimage.image}`" alt="Product Image" class="slider-image" />
            <span class="delete-icon" @click="deleteImage(pimage, index)">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
            </span>
          </div>
        </Slide>
        <template #addons>
          <Navigation />
          <Pagination />
        </template>
      </Carousel>
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
  /* padding: 10px; */
  border-radius: 10px;
  width: 380px;
  text-align: center;
  position: relative;
  /* display: block; */
}
.product-image {
  width: 100%;
  height: auto;
  text-align: center;
  object-fit: cover;
  border-radius: 10px;
  overflow: hidden;

}
.product-actions {
  /* margin-top: 10px; */
  width: 100%;
  display: flex;
  justify-content: space-around;
  position: absolute;
  bottom: 0;
}
.product-info {
  width: 100%;
  /* padding: 0 10px; */
  display: flex;
  justify-content: space-around;
   /* margin-bottom: 4px; */
  position: absolute;
  top: 0;
  background: rgba(255, 255, 255, 0.8); 
}
p {
  padding-bottom: 0;
  margin-bottom: 0;
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
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  width: 100%;
  align-items: center;
  z-index: 10;
}
.popup-content {
  background: #fff;
  margin-top: 20px;
  padding: 20px ;
  border-radius: 10px;
  width: 100%;
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

.upload-image-group {
  /* display: flex;
  justify-content: center;
  align-items: center; */
  width: 100%;
  height: fit-content;
  /* max-height: 10px; */
  border: 2px dashed #d1d5db;
  border-radius: 5px;
}
.image-plus {
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
/* .delete-icon {
  position: absolute;
  top: 5px;
  right: 5px;
  width: 20px;
  height: 20px;
  background: rgba(236, 236, 236, 0.466);
  color: #fff;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
} */
form button, .product-buttons {
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

.product-actions {
  display: flex;
  justify-content: space-between;
}

.product-actions button{

  padding: 6px 10px;
  font-size: 1rem;
  color: #555;
  background-color: #e0e0e0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.product-actions button:hover {
  background-color: #d0d0d0;
}


.image-group {
  width: ;
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto; /* Ensure horizontal scroll if images overflow */
  gap: 8px; /* Space between the images */
}

.single-image {
  position: relative;
  padding: 8px;
  flex: 0 0 auto;
  display: inline-block;
  background-color: #f3f3f3;
  border-radius: 8px;
  overflow: hidden;
}

.img-thumbnail {
  width: 100px; /* Adjust size as needed */
  height: 80px; /* Adjust size as needed */
  object-fit: cover;
  border-radius: 8px; /* Ensure the image has rounded corners */
}

.delete-icon {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 20px;
  height: 20px;
  background-color: #e3342f;
  border: 2px solid white;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.delete-icon span {
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.broadcast {
  border: 1px solid #eee;
  border-radius: 0.25rem;
  display: flex;
  padding: 0.5rem 0.75rem;
}

.broadcast > .vue-feather {
  margin-right: 0.5rem;
}

.broadcast > .vue-carousel {
  flex: 1;
}

.broadcast-content {
  align-items: center;
  display: flex;
  justify-content: space-between;
}




.popupImages {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.broadcast {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  position: relative;
  max-width: 90%;
  max-height: 80%;
  overflow: auto;
}

.carousel__item {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.slider-image {
  max-width: 100%;
  max-height: 500px;  /* Adjust as needed */
  object-fit: cover;
  border-radius: 8px;
}



/* .avatar-uploader .el-upload {
  border: 1px dashed var(--el-border-color);
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: var(--el-transition-duration-fast);
}

.avatar-uploader .el-upload:hover {
  border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  text-align: center;
} */

@media (max-width: 768px) {
  .img-thumbnail {
    width: 80px;
    height: 64px;
  }
}

@media (max-width: 480px) {
  .img-thumbnail {
    width: 60px;
    height: 48px;
  }
}
.form-popup {
    position: absolute;
    width: 90%;
    height: 100%;
    }

/* Responsive styles */
@media only screen and (max-width: 600px) {
    .product-list{
        width: 100%;
        height: fit-content;
        position: relative;
    }

    /* Adjust the popup to be positioned relative to the product */
    .popup {
      position: fixed; /* Change to fixed for consistent positioning relative to the viewport */
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      max-width: fit-content;
      align-items: center;
      z-index: 10;
      pointer-events: auto;
      overflow: auto;
    }

    .product-card {
        width: 100%;
        text-align: left;
    }

    .product-image {
        /* margin-top: 30px; */
        width: 100%;
    }
    .el-upload {
      width: 20%;
      height: 20%;
  }
}
</style>
