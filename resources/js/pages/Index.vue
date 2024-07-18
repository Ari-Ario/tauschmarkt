<script setup>
import { ref, onBeforeMount, defineProps } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Header from '../components/header/Header.vue';
import FooterUser from '../components/footer/FooterUser.vue';
import { useRouter } from 'vue-router';
import { useCart } from '../store'; // Adjust the path as needed
import { useAuthStore } from '@/stores/AuthStore';
import { authClient } from '../services/AuthService';

const store = useAuthStore();
const router = useRouter();
const enterpriseId = router.currentRoute.value.params.id;
const products = ref([]);
const filteredProducts = ref([]);
const filterPrices = ref({ prices: [0, 10000] });
const mobileFiltersOpen = ref(false);
const imagesMode = ref(false);
const product_images = ref([]);

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

function getProductImage(product) {
  if (product.product_images && product.product_images.length > 0) {
    return `/${product.product_images[0].image}`;
  } else {
    return '../assets/Placeholder-enterprise.png';
  }
}

onBeforeMount(() => {
  fetchProduct();
});

const props = defineProps({
  products: Array,
});

// Using the cart store
const { addToCart } = useCart();

const addToCartHandler = (product) => {
  if (product.quantity <= 2) {
    Swal.fire({
        toast: true,
        icon: "info",
        position: "top-end",
        showConfirmButton: false,
        title: "Beachten Sie übrige Quantität: ",
        timer: 6000
      });
  } else if ( product.quantity < 1 ) {
    return;
  }
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.post(`/api/cart/store/${product.id}`, product, {
    quantity: product.quantity,
    price: product.price,
    seller_id: product.seller_id,
  }, {
    headers: {
      'Content-Type': 'multipart/form-data',
      'X-CSRF-TOKEN': csrfToken,
    },
  })
  .then(response => {
    const flash = response.data.flash;
    if (product.quantity > 2 && flash && flash.success) {
      Swal.fire({
        toast: true,
        icon: "success",
        position: "top-end",
        showConfirmButton: false,
        title: flash.success,
        timer: 3000
      });
    }
    addToCart(product); // Add to the reactive store
  })
  .catch(error => {
    console.error('Error adding to cart:', error);
  });
};


//Review popup
const reviews = ref([]);
const averageRating = ref(0);
const reviewMode = ref(false);
const currentProduct = ref({});
const newComment = ref('');
const newRating = ref(0);

const openReviewPopup = async (product) => {
  currentProduct.value = product;
  try {
    const response = await axios.get(`/api/product-review/${product.id}`);
    reviews.value = response.data;
    console.log(reviews.value)
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
  if (!store.authUser) {
      Swal.fire({
        toast: true,
        icon: "info",
        position: "top-end",
        showConfirmButton: false,
        title: "Melden Sie sich an!",
        timer: 3000
      });
      return
    }; // Don't let comments or ratings of non-users

  if (!newComment.value.trim() || !newRating.value) {
      Swal.fire({
        toast: true,
        icon: "warning",
        position: "top-end",
        showConfirmButton: false,
        title: "entweder Sterne oder Komment is leer!",
        timer: 3000
      });
      return
    }; // Don't submit empty comments or ratings

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  try {
    await axios.post(`/api/product-review`, {
      user_id: store.authUser.id,
      product_id: product.id,
      comment: newComment.value,
      rating: newRating.value
    });

    // Fetch the updated reviews
     newComment.value = "";
     newRating.value = 0;
    await openReviewPopup(product);
    Swal.fire({
        toast: true,
        icon: "success",
        position: "top-end",
        showConfirmButton: false,
        title: "Ihre Kommnt wurde gepostet!",
        timer: 3000
      })
  } catch (error) {
    console.error('Failed to submit comment:', error);
    Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Komment wurde nicht gepostet!'
      });
  }
};

const deleteComment = async (review) => {
  try {
    await axios.delete(`/api/product-review/${review.id}`);
    // Handle successful deletion, e.g., refresh comments list
    Swal.fire({
        toast: true,
        icon: "success",
        position: "top-end",
        showConfirmButton: false,
        title: "Ihre Komment wurde gelöscht!",
        timer: 3000
      })
      // product.value.id = review.product_id;
    await openReviewPopup(currentProduct);

  } catch (error) {
      console.error('Failed to delete comment:', error);
      // Handle the error
      Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Komment wurde nicht gelöscht!'
      });
}
}

const updateRating = (star) => {
  newRating.value = star;
};
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
                <p>{{ product.quantity }} übrig</p>
                <p v-if="product.amount">{{ product.price }}CHF /{{ product.amount }}{{ product.mass_unit }} </p>
                <p v-else>{{ product.price }}CHF /{{ product.mass_unit }} </p>

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
                <button @click="addToCartHandler(product)" :disabled="product.quantity < 1">
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

              <div v-if="reviewMode && currentProduct.id === product.id" class="popupReviews" @click.self="closeReviewPopup()">
                <div class="review-section">
                  <div @click="closeReviewPopup" class="close-label">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                  </div>
                  <div class="stars">
                    <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= averageRating }">&#9733;</span>
                  </div>
                  <img :src="getProductImage(product)" alt="Product Image" class="product-image" />
                  <div class="review-header">
                    <div class="stars">
                      <span v-for="star in 5" :key="star" class="star star-rating" :class="{ filled: star <= newRating }" @click="updateRating(star)">
                        &#9733;
                      </span>
                    </div>
                  </div>
                  <div class="comment-box">
                    <textarea v-model="newComment" placeholder="Komment schreiben ..."></textarea>
                    <label @click="submitComment(currentProduct)">
                      <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#298E46"><path d="M120-160v-640l760 320-760 320Zm66.67-102 520.66-218-520.66-220v158.67L428-480l-241.33 60v158Zm0 0v-438 438Z"/></svg>             
                    </label>
                  </div>
                  <div class="comments-list">
                    <div v-for="review in reviews" :key="review.id" class="comment">
                      <div class="comment-part">
                        <p><strong>{{ review.user.firstname }}:</strong> {{ review.comment }}</p>
                        <p style="text-align: left;">Bewertung: {{ review.rating }} </p>
                          <!-- <div class="stars">
                            <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= review.rating }" @click="updateRating(star)">
                              &#9733;
                            </span>
                          </div> -->
                      </div>
                        <div class="bin" v-if="review.user_id && store.authUser" @click="deleteComment(review)">
                          <svg v-if="review.user_id == store.authUser.id" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EA3323"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>                    </div>
                        </div> 

                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    </main>
  </div>
  <div class="footer">
      <FooterUser />
  </div>
</template>


<style scoped>
/* Container and Grid */
.container {
  max-width: 100%;
  padding-right: 1rem;
  padding-left: 1rem;
  margin-top: 100px;
  margin-right: auto;
  margin-left: auto;
}

@media (min-width: 576px) {
  .container {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
  }
}

.row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}

.col {
  flex-basis: 0;
  flex-grow: 1;
  max-width: 100%;
  padding-right: 15px;
  padding-left: 15px;
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
  margin-bottom: .5rem;
  font-family: inherit;
  font-weight: 500;
  line-height: 1.2;
}

h1 {
  font-size: 2.5rem;
}

h2 {
  font-size: 2rem;
}

h3 {
  font-size: 1.75rem;
}

h4 {
  font-size: 1.5rem;
}

h5 {
  font-size: 1.25rem;
}

h6 {
  font-size: 1rem;
}

p {
  margin-top: 0;
  margin-bottom: 1rem;
}

/* Buttons */
.btn {
  display: inline-block;
  font-weight: 400;
  color: #212529;
  text-align: center;
  vertical-align: middle;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: .375rem .75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: .25rem;
}

.btn:hover {
  color: #212529;
  text-decoration: none;
}

.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  color: #fff;
  background-color: #0056b3;
  border-color: #0056b3;
}

/* Form Elements */
.form-control {
  display: block;
  width: 100%;
  padding: .375rem .75rem;
  font-size: 1rem;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: .25rem;
  transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.form-control:focus {
  color: #495057;
  background-color: #fff;
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
}

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


/* Utilities */
.text-center {
  text-align: center !important;
}

.mt-0 {
  margin-top: 0 !important;
}

.mt-1 {
  margin-top: .25rem !important;
}

.mt-2 {
  margin-top: .5rem !important;
}

.mt-3 {
  margin-top: 1rem !important;
}

.mt-4 {
  margin-top: 1.5rem !important;
}

.mt-5 {
  margin-top: 3rem !important;
}

.mb-0 {
  margin-bottom: 0 !important;
}

.mb-1 {
  margin-bottom: .25rem !important;
}

.mb-2 {
  margin-bottom: .5rem !important;
}

.mb-3 {
  margin-bottom: 1rem !important;
}

.mb-4 {
  margin-bottom: 1.5rem !important;
}

.mb-5 {
  margin-bottom: 3rem !important;
}

.p-0 {
  padding: 0 !important;
}

.p-1 {
  padding: .25rem !important;
}

.p-2 {
  padding: .5rem !important;
}

.p-3 {
  padding: 1rem !important;
}

.p-4 {
  padding: 1.5rem !important;
}

.p-5 {
  padding: 3rem !important;
}

/* Custom Styles */
.bg-white {
  background-color: #fff !important;
}

.bg-gray-50 {
  background-color: #f9fafb !important;
}

.text-gray-900 {
  color: #1a202c !important;
}

.text-gray-600 {
  color: #718096 !important;
}

.text-gray-500 {
  color: #6b7280 !important;
}

.text-gray-400 {
  color: #9ca3af !important;
}

.text-indigo-600 {
  color: #4f46e5 !important;
}

.focus\:ring-indigo-500:focus {
  --tw-ring-color: #6366f1;
}

.border-gray-200 {
  border-color: #edf2f7 !important;
}

.border-gray-300 {
  border-color: #e2e8f0 !important;
}

.border-gray-600 {
  border-color: #718096 !important;
}

.border-primary-500 {
  border-color: #6366f1 !important;
}

.focus\:border-primary-500:focus {
  border-color: #6366f1 !important;
}

.rounded-lg {
  border-radius: .5rem !important;
}

.rounded-md {
  border-radius: .375rem !important;
}

.rounded {
  border-radius: .25rem !important;
}

.shadow-xl {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

/* Layout */
.max-w-7xl {
  max-width: 80rem;
}

.max-w-xs {
  max-width: 20rem;
}

.flex {
  display: flex !important;
}

.items-center {
  align-items: center !important;
}

.items-baseline {
  align-items: baseline !important;
}

.justify-between {
  justify-content: space-between !important;
}

.justify-center {
  justify-content: center !important;
}

.self-end {
  align-self: flex-end !important;
}

.hidden {
  display: none !important;
}

.block {
  display: block !important;
}

.inline-block {
  display: inline-block !important;
}

.w-full {
  width: 100% !important;
  font-weight: bold;
  color: #004d40;

}

.w-1\3 {
  width: 33.333333% !important;
}

.m-auto {
  margin: auto !important;
}

/* Transitions */
.transition {
  transition: all 0.2s ease-in-out;
}

.ease-linear {
  transition-timing-function: linear;
}

.ease-in-out {
  transition-timing-function: ease-in-out;
}

.duration-300 {
  transition-duration: 300ms;
}

/* Spacing */
.pt-24 {
  padding-top: 6rem !important;
}

.pb-24 {
  padding-bottom: 6rem !important;
}

.py-6 {
  padding-top: 1.5rem !important;
  padding-bottom: 1.5rem !important;
}

.py-3 {
  padding-top: 0.75rem !important;
  padding-bottom: 0.75rem !important;
}

.px-4 {
  padding-right: 1rem !important;
  padding-left: 1rem !important;
}

.px-6 {
  padding-right: 1.5rem !important;
  padding-left: 1.5rem !important;
}

.px-8 {
  padding-right: 2rem !important;
  padding-left: 2rem !important;
}

.menu {
  border-bottom-width: 1px;
  /* position: relative; */
  background-color: #ffffff;
  /* display: flex;
  justify-content: center;
  align-items: center; */
}

.dropdown {
    position: absolute ;
    z-index: 10;
    background-color: #ffffff;
    width: 100%;

}

/* Responsive */
@media (min-width: 769px) {
  .sm\:px-6 {
    padding-right: 1.5rem !important;
    padding-left: 1.5rem !important;
  }

  .sm\:ml-7 {
    margin-left: 1.75rem !important;
  }

  .lg\:col-span-3 {
    grid-column: span 3 / span 3 !important;
  }

  .lg\:grid-cols-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
  }

  .lg\:hidden {
    display: none !important;
  }

  .lg\:block {
    display: block !important;
  }
}

@media (max-width: 768px) {
  .w-full {
    width: 100% !important;
    /* text-align: center; */
  }
  .bt-1 {
    display: flex;
    justify-content: center;
    max-height: 30px;
    margin-top: 24px;
    text-align: center;
  }

  .sr-only{
    display: none;
  }
  .w-full {
  width: 80% !important;
}
  .sm\:px-6 {
    padding-right: 1.5rem !important;
    padding-left: 1.5rem !important;
  }

  .lg\:col-span-3 {
    grid-column: span 3 / span 3 !important;
  }

  .lg\:grid-cols-4 {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
  }

  .lg\:hidden {
    display: none !important;
  }

  .lg\:block {
    display: block !important;
  }
}


/* style of the review popup  */




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
  z-index: 2;
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
  justify-content: center;
  align-items: center;
  width: 100%;
  /* border: 1px solid black; */
}

.star {
  font-size: 24px;
  color: #ccc;
  cursor: pointer;
}
.star-rating {
  font-size: 48px;

}

.star.filled {
  color: #ffb400;
}

.comment-box {
  display: flex;
  /* flex-direction: column; */
  margin-bottom: 16px;
}

.comment-box textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.comment-box label {
  /* align-self: flex-end; */
  padding:  10px;
  border: none;
  /* border: 2px solid #298e46; */
  border-radius: 50%;
  /* background-color: #298e46; */
  color: #fff;
  /* border-radius: 4px; */
  cursor: pointer;
  text-align: center;
}

.comments-list {
  max-height: 300px;
  overflow-y: auto;
}

.comment {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border-bottom: 1px solid #ccc;
}

.bin {
  cursor: pointer;
}
.close-label {
  cursor: pointer;
  text-align: end;
}

</style>  