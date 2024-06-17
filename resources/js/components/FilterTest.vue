<template>
    <div>
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
        
        <div v-for="enterprise in filteredEnterprises" :key="enterprise.id" class="card">
            <div class="Photo">
                <img class="Photo" :src="getEnterprisePicture(enterprise.enterprise_picture)" alt="" />
                <div class="favorite" @click="addOrRemoveFavorites(enterprise)">
                    <svg v-if="enterprise.is_favorite" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" width="24px" height="24px">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24" width="24px" height="24px">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
            </div>
            <div class="details">
                <div class="user-details">
                    <div class="user-photo">
                        <img class="user-photo" :src="getProfilePicture(enterprise.profile_picture)" />
                    </div>
                    <p class="user-name description">{{ enterprise.firstname }} {{ enterprise.lastname }}</p>
                    <div @click="openEnterprise(enterprise.id)" class="enterprise-btn">
                        <router-link :to="{ name: 'index' , params: { id: enterprise.id } }" custom v-slot="{ navigate }">
                            <div class="link" @click="navigate" role="link">
                                <div class="link" id="enterpriseButton">Besuchen</div>
                            </div>
                        </router-link>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const categories = ref([]);
        const selectedCategories = ref([]);
        const enterprises = ref([]);
        const filteredEnterprises = ref([]);

        const loadEnterprises = async () => {
            try {
                const response = await axios.get(`api/enterprises/${userId}`);
                enterprises.value = response.data;
                filterEnterprises();
            } catch (error) {
                console.error("Error loading enterprises:", error);
            }
            try {
                const response = await axios.get(`/api/product/${1}`);
                categories.value = response.data.categories;
            } catch (error) {
                console.error('Failed to fetch Product:', error);
            }
        };

        const filterEnterprises = () => {
            if (selectedCategories.value.length === 0) {
                filteredEnterprises.value = enterprises.value;
            } else {
                filteredEnterprises.value = enterprises.value.filter(enterprise =>
                    selectedCategories.value.includes(enterprise.categoryId)
                );
            }
        };

        const getEnterprisePicture = (picture) => {
            // Logic to get enterprise picture
        };

        const getProfilePicture = (picture) => {
            // Logic to get profile picture
        };

        const addOrRemoveFavorites = (enterprise) => {
            // Logic to add or remove favorites
        };

        const openEnterprise = (id) => {
            // Logic to open enterprise details
        };

        watch(selectedCategories, filterEnterprises);

        onMounted(() => {
            axios.defaults.withCredentials = true;
            loadEnterprises();
        });

        return {
            categories,
            selectedCategories,
            enterprises,
            filteredEnterprises,
            getEnterprisePicture,
            getProfilePicture,
            addOrRemoveFavorites,
            openEnterprise,
        };
    }
};
</script>

<style>
/* Add your CSS styles here */
</style>
