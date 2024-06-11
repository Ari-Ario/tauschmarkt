
<script setup>
// import UserLayouts from './Layouts/UserLayouts.vue'
import { ref, onBeforeMount, watch, defineProps } from 'vue'
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
// import Products from '../User/Components/Products.vue'
// import SecondaryButtonVue from '@/Components/SecondaryButton.vue'
import Swal from 'sweetalert2';
import { defineComponent } from 'vue'
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'
import axios from 'axios'

import Header from '../components/header/Header.vue';
import {useRouter} from 'vue-router';

const router = useRouter();
// import { convertToHtml } from '@/components/Creator.vue';
const enterpriseId = router.currentRoute.value.params.id;
// console.log(enterpriseId);
const enterprise = ref(null)
const categories = ref([]);
const products = ref([]);

const fetchProduct = async () => {
  // console.log(sellerId);

    try {
        const response = await axios.get(`/api/product/${enterpriseId}`);
        categories.value = response.data.categories;
        products.value = response.data.products.data;
        // console.log(products.value)
        // console.log(categories.value)

    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
};
onBeforeMount(() => {
  fetchProduct();
});

const product_images = ref([]);
const imagesMode = ref(false);

const openImagesPopup = async (product) => {
  product_images.value = product.product_images;
  // console.log(productImages)
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

const sortOptions = [
  { name: 'Most Popular', href: '#', current: true },
  { name: 'Best Rating', href: '#', current: false },
  { name: 'Newest', href: '#', current: false },
  { name: 'Price: Low to High', href: '#', current: false },
  { name: 'Price: High to Low', href: '#', current: false }
]

const filterPrices = ref({ prices: [0, 100000] })

const priceFilter = async () => {
  try {
    const response = await axios.get('products', {
      params: {
        prices: {
          from: filterPrices.value.prices[0],
          to: filterPrices.value.prices[1]
        }
      }
    })
    // Handle the response here
  } catch (error) {
    console.error('Error filtering prices:', error)
  }
}

const mobileFiltersOpen = ref(false)

const props = defineProps({
  products: Array,
  categories: Array
})

// Method for adding product to cart
const addToCart = (product) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  console.log(enterpriseId);
    axios.post(`/api/cart/store/${product.id}`, product,  {
      quantity: product.quantity,
      price: product.price,
      seller_id: product.seller_id,
    }, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': csrfToken,
            },
        }) // Update the endpoint as necessary
        .then(response => {
          console.log(response)
            const flash = response.data.flash;
            if (flash && flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: flash.success,
                    timer: 3000
                });
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
        });
};
const selectedCategories = ref([])

watch([ selectedCategories], updateFilteredProducts)

async function updateFilteredProducts() {
  try {
    const response = await axios.get('products', {
      params: {
        categories: selectedCategories.value
      }
    })
    // Handle the response here
  } catch (error) {
    console.error('Error updating filtered products:', error)
  }
}

const openMobileFilters = () => {
  mobileFiltersOpen.value = true
}

const closeMobileFilters = () => {
  mobileFiltersOpen.value = false
}
</script>

<template>
<Header></Header>

<div class="bg-white container">

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Produkte</h1>

        <div class="flex items-center">
          <Menu as="div" class="relative inline-block text-left">
            <div>
              <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                Sort
                <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
              </MenuButton>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
              <MenuItems class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="py-1">
                  <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                    <a :href="option.href" :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{ option.name }}</a>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>

          <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden" @click="openMobileFilters">
            <span class="sr-only">Filters</span>
            <FunnelIcon class="h-5 w-5" aria-hidden="true" />
          </button>
        </div>
      </div>

      <section aria-labelledby="products-heading" class="pb-24 pt-6">

        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
          <!-- Filters -->
          <form class="hidden lg:block">
            <h3 class="sr-only">Prices</h3>
            <!-- price filter -->
            <div class="flex items-center justify-between space-x-3">
              <div class="basis-1/3">
                <label for="filters-price-from" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                  From
                </label>

                <input type="number" id="filters-price-from" placeholder="Min price" v-model="filterPrices.prices[0]" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
              </div>
              <div class="basis-1/3">
                <label for="filters-price-to" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                  To
                </label>

                <input type="number" id="filters-price-to" v-model="filterPrices.prices[1]" placeholder="Max price" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
              </div>
              <button class="self-end" @click="priceFilter()">
                Ok
              </button>
            </div>

            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton
                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Categories</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="category in categories" :key="category.id"
                            class="flex items-center">
                            <input :id="`filter-${category.id}`" :value="category.id" type="checkbox"
                                v-model="selectedCategories"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <label :for="`filter-${category.id}`" class="ml-3 text-sm text-gray-600">{{
                                category.name }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
          </form>

          <!-- Product grid -->
          <div class="lg:col-span-3">
            <!-- Your content -->
            <!-- <Products :products="products.data" /> -->
            <div class="product-list">
              <div v-for="product in products" :key="product.id" class="product-card">
                <div class="product-info">
                  <p>{{ product.name }}</p>
                  <p>Quantität: {{ product.quantity }}</p>
                </div>
                <img :src="getProductImage(product)" alt="Product Image" class="product-image" />
                <div class="product-actions">
                  <button class="product-buttons" @click="openImagesPopup(product)">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>        </button>
                  <button @click="addToCart(product)">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg>                      
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
        </div>
      </section>
    </main>
</div>
</template>

<style scoped>
/* Container and Grid */
.container {
  max-width: 100%;
  padding-right: 1rem;
  padding-left: 1rem;
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

/* Responsive */
@media (min-width: 768px) {
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

</style>  