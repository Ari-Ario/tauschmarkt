<script setup>
import { useAuthStore } from '@/stores/AuthStore';
import AuthService from "@/services/AuthService";
import { authClient } from '../services/AuthService';

import { ref, watch, onMounted } from "vue";
import axios from "axios";
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { PlusIcon, MinusIcon } from '@heroicons/vue/20/solid';
import CategoryTags from "./CategoryTags.vue";
import { RouterLink } from "vue-router";
import { location, distance } from '../locals';

// import { categories, selectedCategories, updateFilteredProducts } from '../categoryFilterScript';

const store = useAuthStore();
const userId = store?.authUser?.id;
const enterprises = ref([]);
const filteredEnterprises = ref([]);
const categories = ref([]);
const selectedCategories = ref([]);

const loadEnterprises = async (latitude, longitude, distance, userId) => {
    try {
        const response = await axios.get(`/api/enterprises/${userId}`, {
          params: {
            latitude: location.value.latitude,
            longitude: location.value.longitude,
            distance: distance.value,
          },
        });
        enterprises.value = response.data;
        filterEnterprises();
    } catch (error) {
        console.error("Error loading enterprises:", error);
    }
    try {
        const response = await axios.get(`/api/product/${1}`);
        categories.value = response.data.categories;
        // console.log(categories)
    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
};

const filterEnterprises = () => {
    if (selectedCategories.value.length === 0) {
        filteredEnterprises.value = enterprises.value;
    } else {
        filteredEnterprises.value = enterprises.value.filter(enterprise => {
            if (!enterprise.categories) {
                console.warn(`Enterprise ${enterprise.id} has no categories`);
                return false;
            }
            return enterprise.categories.some(categoryId =>
                selectedCategories.value.includes(categoryId)
            );
        });
    }
};

watch(selectedCategories, filterEnterprises);

const addOrRemoveFavorites = async (enterprise) => {
    try {
        enterprise.is_favorite = !enterprise.is_favorite;
        if (enterprise.is_favorite) {
            let response = await authClient.post("/favorites/add", {
                enterpriseId: enterprise.id,
                userId: store.authUser.id,
                isFavorite: enterprise.is_favorite
            });
            // console.log(response);
        } else {
            let response = await authClient.post("/favorites/remove", {
                enterpriseId: enterprise.id,
                userId: store.authUser.id,
                isFavorite: enterprise.is_favorite
            });
            // console.log(response);
        }
    } catch (error) {
        console.error("Error adding to favorites:", error);
    }
};

const showPopup = ref({}); 
const togglePopup = (enterpriseId) => {
    showPopup.value[enterpriseId] = !showPopup.value[enterpriseId];
};

const getEnterpriseCategories = (enterprise) => {
    return enterprise.categories.map(categoryId => {
        return categories.value.find(category => category.id === categoryId)?.name;
    }).filter(name => name);
};

onMounted(() => {
    loadEnterprises(location.latitude, location.longitude, distance, userId);
});

const getEnterprisePicture = (path) => {
    return path ? `storage/${path}` : 'storage/enterprise_images/default.png';
};

const getProfilePicture = (path) => {
    return path ? `storage/${path}` : 'storage/profile_images/default.png';
};
</script>

<template>
    <div class="enterprises">
        <h1 style="text-align: center; color:#004d40;">Kleinhaendler</h1>

        <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
            <h3 class="-my-3 flow-root">
                <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500" style="height: 40px; display: flex; justify-content: space-between;">
                    <span class="font-medium text-gray-900">Categories</span>
                    <span class="ml-6 flex items-center">
                        <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" style="height: 40px;" />
                        <MinusIcon v-else class="h-5 w-5" aria-hidden="true" style="height: 40px;" />
                    </span>
                </DisclosureButton>
            </h3>
            <DisclosurePanel class="pt-6 dropdown">
                <div class="space-y-4">
                    <div v-for="category in categories" :key="category.id" class="flex items-center">
                        <input :id="`filter-${category.id}`" :value="category.id" type="checkbox" v-model="selectedCategories"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <label :for="`filter-${category.id}`" class="ml-3 text-sm text-gray-600">{{ category.name }}</label>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <div class="card" v-for="enterprise in filteredEnterprises" :key="enterprise.id">
            <div class="Photo">
                <img class="Photo" :src="getEnterprisePicture(enterprise.enterprise_picture)" alt="" />
                <div v-if="!store?.authUser?.is_seller" class="favorite" @click="addOrRemoveFavorites(enterprise)">
                    <svg v-if="enterprise.is_favorite" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
            <div class="details">
                <div class="user-details">
                    <div class="user-photo">
                        <img class="user-photo" :src="getProfilePicture(enterprise.profile_picture)" />
                    </div>
                    <h3 class="user-name description">
                        {{ enterprise.firstname }} {{ enterprise.lastname }} 
                    </h3>
                    
                  <!-- label with a popup at its top by clicking here -->
                   <div v-if="(enterprise.categories.length) > 0" >
                    <label @click="togglePopup(enterprise.id)" class="category-label">
                      {{ getEnterpriseCategories(enterprise)[0] }}
                      <span v-if="(enterprise.categories.length) > 1">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#004d40"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg> -->
                        +
                      </span>
                    </label>
                  </div>

                  <div v-if="showPopup[enterprise.id] && (enterprise.categories.length) > 1" class="popup">
                    <ul>
                      <li v-for="category in getEnterpriseCategories(enterprise).slice(1)" :key="category">
                        {{ category }}
                      </li>
                    </ul>
                  </div>

                  <div @click="openEnterprise(enterprise.id)" class="enterprise-btn">
                    <router-link :to="{ name: 'index' , params: { id: enterprise.id } }" customv-slot="{ navigate }">
                        <div class="link" @click="navigate" role="link">
                            <div class="link" id="enterpriseButton">
                                Besuchen
                            </div>
                        </div>
                    </router-link>                
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

/* category popup style */
.category-label{
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  color: #004d40;
  font-weight: bold;
  display: inline-block;
  padding: 8px;
  position: relative;
  border-radius: 8px;
  border: 1px solid #004d40;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.popup {
  position: absolute;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  z-index: 1001;
  border: 1px solid #004d40;
  padding: 5px;
  width: 200px;
  bottom: 110%;
  left: 50%;
  transform: translateX(-50%);
}

.popup ul {
  list-style: none;
  padding: 0;
  margin: 0;
  color: #004d40;
}

.popup li {
  padding: 5px 0;
  border-bottom: 1px solid #004d40;
}

.popup li:last-child {
  border-bottom: none;
}

/* card style */
.card {
    margin: 20px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #ffffff;
    max-width: 1200px;
    border: 1px solid rgba(0, 0, 0, 0.523);
    overflow: hidden; /* Ensure content doesn't overflow the card */
}

.Photo {
    width: 100%;
    position: relative;
    /* max-height: 600px; */
}

.Photo img {
    width: 100%;
    height: auto;
}

.favorite {
    width: 100%;
    padding: 10px;
    position: absolute;
    top: 0;
    background: rgba(255, 255, 255, 0.8); /* Optional: Add a background color for readability */
}

.details {

    width: 100%;
    padding: 10px;
    position: sticky;
    bottom: 0;
    border: 1px solid;
    background: rgba(255, 255, 255, 0.8); /* Optional: Add a background color for readability */
}

.user-photo {
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
}

.user-photo img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.user-details {
    display: flex;
    justify-content: space-around;

    /* justify-content: space-between; */
    align-items: center;
}

p {
    margin: 0;
}
#enterpriseButton, .btn {
    border-radius: 5px;
    background-color:   #004d40;
    color: white;
    padding: 10px;
    font-weight: bold;
}

.button {
    all: unset;
    width: fit-content;
    background: #004d40;
    border-radius: 50px;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    margin-left: 15px;
    padding: 10px 20px;
}

/* Responsive styles for card*/
@media only screen and (max-width: 600px) {
  .enterprises {
    margin-bottom: 45px;
  }
    .card {
        width: 90%;
        height: fit-content;
    }

    .details {
        width: 100%;
        text-align: left;
    }

    .button {
        margin-top: 30px;
        width: 50%;
    }

    .border-b {
        margin: 0 24px 0 24px;
    }
    .user-name {
      display: none;
    }
    .category-label , .enterprise-btn {
      font-size: smaller;
    }
}



/* style for categories div */

.border-b {
  border-bottom-width: 1px;
  background-color: #ffffff;
  /* position: relative;
  display: flex;
  justify-content: space-between; */
}

.dropdown {
    position: absolute ;
    z-index: 10;
    background-color: #ffffff;
    width: 100%;

}
.border-gray-200 {
  --tw-border-opacity: 1;
  border-color: rgba(229, 231, 235, var(--tw-border-opacity));
}

.py-6 {
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
}

.-my-3 {
  margin-top: -0.75rem;
  margin-bottom: -0.75rem;
}

.flow-root {
  display: flow-root;
}

.flex {
  display: flex;
}

.w-full {
  width: 100%;
}

.bg-white {
  --tw-bg-opacity: 1;
  background-color: rgba(255, 255, 255, var(--tw-bg-opacity));
}

.py-3 {
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
}

.text-sm {
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.text-gray-400 {
  --tw-text-opacity: 1;
  color: rgba(156, 163, 175, var(--tw-text-opacity));
}

.hover\:text-gray-500:hover {
  --tw-text-opacity: 1;
  color: rgba(107, 114, 128, var(--tw-text-opacity));
}

.font-medium {
  font-weight: 500;
}

.text-gray-900 {
  --tw-text-opacity: 1;
  color: rgba(17, 24, 39, var(--tw-text-opacity));
}

.ml-6 {
  margin-left: 1.5rem;
}

.items-center {
  align-items: center;
}

.pt-6 {
  padding-top: 1.5rem;
}

.space-y-4 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-y-reverse: 0;
  margin-top: calc(1rem * calc(1 - var(--tw-space-y-reverse)));
  margin-bottom: calc(1rem * var(--tw-space-y-reverse));
}

.h-4 {
  height: 1rem;
}

.w-4 {
  width: 1rem;
}

.rounded {
  border-radius: 0.25rem;
}

.border-gray-300 {
  --tw-border-opacity: 1;
  border-color: rgba(209, 213, 219, var(--tw-border-opacity));
}

.text-indigo-600 {
  --tw-text-opacity: 1;
  color: rgba(79, 70, 229, var(--tw-text-opacity));
}

.focus\:ring-indigo-500:focus {
  --tw-ring-color: rgba(99, 102, 241, var(--tw-ring-opacity));
}

.ml-3 {
  margin-left: 0.75rem;
}

.text-gray-600 {
  --tw-text-opacity: 1;
  color: rgba(75, 85, 99, var(--tw-text-opacity));
}

</style>