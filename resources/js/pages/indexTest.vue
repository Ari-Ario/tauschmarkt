<script setup>
import { ref, onBeforeMount, defineProps } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Header from '../components/header/Header.vue';
import { useRouter } from 'vue-router';
import { useCart } from '../store'; // Adjust the path as needed

const router = useRouter();
const enterpriseId = router.currentRoute.value.params.id;
const products = ref([]);
const filteredProducts = ref([]);
const filterPrices = ref({ prices: [0, 100000] });
const mobileFiltersOpen = ref(false);
const imagesMode = ref(false);
const product_images = ref([]);

const reviews = ref([]);
const averageRating = ref(0);
const reviewMode = ref(false);
const currentProduct = ref({});
const newComment = ref('');

const fetchProduct = async () => {
  try {
    const response = await axios.get(`/api/product/${enterpriseId}`);
    products.value = response.data.products.data;
    filterProductsByPrice(); // Initial filter
  } catch (error) {
    console.error('Failed to fetch Product:', error);
  }
};

const filterProductsByPrice = () => {
  const [minPrice, maxPrice] = filterPrices.value.prices;
  filteredProducts.value = products.value.filter(product => product.price >= minPrice && product.price <= maxPrice);
};

const openImagesPopup = (product) => {
  product_images.value = product.product_images;
  imagesMode.value = true;
};

const closeImagesPopup = () => {
  imagesMode.value = false;
};




const openReviewPopup = async (product) => {
  currentProduct.value = product;
  try {
    const response = await axios.get(`/api/product-review/${product.id}`);
    reviews.value = response.data.reviews;
    calculateAverageRating();
    reviewMode.value = true;
  } catch (error) {
    console.error('Failed to fetch reviews:', error);
  }
};

const closeReviewPopup = () => {
  reviewMode.value = false;
};

const calculateAverageRating = () => {
  const totalRating = reviews.value.reduce((sum, review) => sum + review.rating, 0);
  averageRating.value = reviews.value.length ? (totalRating / reviews.value.length).toFixed(1) : 0;
};

const submitComment = async (product) => {
  if (!newComment.value.trim()) return; // Don't submit empty comments

  try {
    await axios.post(`/api/product-review`, {
      product_id: product.id,
      comment: newComment.value,
      rating: 5 // Assuming a 5-star rating for simplicity
    });

    // Fetch the updated reviews
    await openReviewPopup(product);
    newComment.value = ''; // Clear the comment box
  } catch (error) {
    console.error('Failed to submit comment:', error);
  }
};

// Initialize by fetching products
fetchProduct();

</script>

<template>
    <Header></Header>
    
    <div class="bg-white container">
      <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <section aria-labelledby="products-heading" class="pb-24 pt-6">
          <div class="prise-form">
            <!-- Filters -->
            <form class="hidden lg:block">
              <div class="flex items-center justify-between space-x-3">
                <h3 class="sr-only">Preis:</h3>
                <div class="basis-1/3">
                  <label for="filters-price-from" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Von</label>
                  <input type="number" id="filters-price-from" placeholder="Min price" v-model="filterPrices.prices[0]" class="block w-full rounded-lg border border-gray-300 " />
                </div>
                <div class="basis-1/3">
                  <label for="filters-price-to" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Bis</label>
                  <input type="number" id="filters-price-to" placeholder="Max price" v-model="filterPrices.prices[1]" class="block w-full rounded-lg border border-gray-300 " />
                </div>
                <div class="basis-1/3">
                    <button type="button" @click="filterProductsByPrice" class="bt-1 mt-6 w-full rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-700">Verwenden</button>
                  </div>
              </div>
            </form>
          </div>
        </section>
        
        <!-- Product grid -->
        <div class="lg:col-span-3">
          <div class="product-list">
            <div v-for="product in filteredProducts" :key="product.id" class="product-card">
              <div class="product-info">
                <p>{{ product.name }}</p>
                <p>Quantität: {{ product.quantity }}</p>
                <p>Preis: {{ product.price }}</p>
              </div>
              <img :src="getProductImage(product)" alt="Product Image" class="product-image" />
              <div class="product-actions">
                <button class="product-buttons" @click="openImagesPopup(product)">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
                    <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                  </svg>
                </button>
    
                <button class="product-buttons" @click="openReviewPopup(product)">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="m363-390 117-71 117 71-31-133 104-90-137-11-53-126-53 126-137 11 104 90-31 133ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>                    
                </button>
    
                <button @click="addToCartHandler(product)">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/>
                  </svg>                      
                </button>
              </div>
    
              <div v-if="imagesMode" class="popupImages" @click.self="closeImagesPopup()">
                <div class="broadcast">
                  <Carousel>
                    <Slide v-for="(pimage, index) in product_images" :key="pimage.id">
                      <div class="carousel__item">
                        <img :src="`/${pimage.image}`" alt="Product Image" class="slider-image" />
                      </div>
                    </Slide>
                    <template #addons>
                      <Navigation />
                      <Pagination />
                    </template>
                  </Carousel>
                </div>
              </div>
    
            </div>
          </div>
        </div>
      </main>
    </div>
    </template>
    
    <style scoped>
.product-list {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
}

.product-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 16px;
  width: 300px;
  position: relative;
}

.product-info {
  margin-bottom: 16px;
}

.product-image {
  width: 100%;
  height: auto;
  margin-bottom: 16px;
}

.product-actions {
  display: flex;
  justify-content: space-between;
}

.product-buttons {
  background: none;
  border: none;
  cursor: pointer;
}

.popupImages, .popupReviews {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.review-section {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 500px;
  max-height: 80%;
  overflow-y: auto;
}

.review-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.stars {
  display: flex;
}

.star {
  font-size: 24px;
  color: #ccc;
}

.star.filled {
  color: #ffb400;
}

.comment-box {
  display: flex;
  flex-direction: column;
  margin-bottom: 16px;
}

.comment-box textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.comment-box button {
  align-self: flex-end;
  padding: 10px 20px;
  border: none;
  background-color: #298e46;
  color: #fff;
  border-radius: 4px;
  cursor: pointer;
}

.comments-list {
  max-height: 300px;
  overflow-y: auto;
}

.comment {
  padding: 10px;
  border-bottom: 1px solid #ccc;
}
</style>
