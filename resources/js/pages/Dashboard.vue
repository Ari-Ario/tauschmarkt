<script setup>
    import { ref, onMounted, watch } from 'vue';

    import { useAuthStore } from '../stores/AuthStore';
    import LogoutButton from '@/components/LogoutButton.vue';
    import FooterUser from '../components/footer/FooterUser.vue';
    import FooterSeller from '../components/footer/FooterSeller.vue';
    import EnterpriseCard from "../components/EnterpriseCard.vue";
    import Header from '../components/header/Header.vue';

    import SellerProfil from '../components/SellerComponents/SellerProfile.vue';
    import SellerProducts from '../components/SellerComponents/SellerProducts.vue';
    import SellerStatistics from '../components/SellerComponents/SellerStatistics.vue';

    import Map from "./Map.vue";
    import axios from 'axios';
    import { useRoute } from 'vue-router';

    const store = useAuthStore();
    const userId = store?.authUser?.id;

    const currentPage = ref('Liste');
    let route = useRoute();
    const user = ref({});
    function switchPage(page) {
        currentPage.value = page;
    }

    const loadUser = async () => {
        try {

            const response = await axios.get(`api/users/auth`); // or 1
            user.value = await response.data.data;
            // console.log(user.value);
        } catch (error) {
            console.error("Error loading blogs:", error);
        }
    };

    onMounted(() => {
        loadUser();
    });
</script>


<template>
    <header>
        <Header />

        <!-- <LogoutButton /> -->
    </header>


    <!-- Seller Profile -->
    <div v-if="user.is_seller" class="content">

        <!-- <div :class="{ active: currentPage !== 'Map' }, menu-container">
            <router-link :to="{ name: 'map'}" class="link">
                <div class="switch-container">

                    <button>Karte📍
                    </button>
                    
                </div>
            </router-link>
        </div> -->
        
        <div v-if="currentPage === 'Liste'" class="content">
            <div class="EnterpriseCardContainer">
                <SellerProfil />
            </div>
            <div class="EnterpriseCardContainer">
                <SellerProducts />
            </div>
            <div class="EnterpriseCardContainer">
                <SellerStatistics />
            </div>
        </div>

        <div v-else-if="currentPage === 'Map'" class="content">
            <!-- Map page content here -->
            <Map />
        </div>

        <div v-show="currentPage !== 'Map'">
            <FooterSeller />
        </div>
    </div>

    <!-- User Profile -->
    <div v-else class="content">
        <div :class="{ active: currentPage !== 'Map' }, menu-container">
            <div class="search-container">
                <input type="text" placeholder="Search" class="search-input" />
                <button class="location-button"
                    :class="{ active: currentPage === 'Search' }" 
                    @click="switchPage('Search')">Los
                </button>

                <button class="filter-button">⚙️</button>

            </div>
            
            <div class="switch-container">
                <button 
                    :class="{ active: currentPage === 'Liste' }" 
                    @click="switchPage('Liste')">Liste
                </button>
                <router-link :to="{ name: 'map'}" class="link">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>        -->
                    <button 
                        :class="{ active: currentPage === 'Map' }" 
                        @click="switchPage('Map')">Karte 📍
                    </button>
                </router-link>
            </div>
        </div>
        <div v-if="currentPage === 'Liste'" class="content">
            <h4 style="text-align: center;" v-if="store.authUser"> Willkomen {{ store.authUser.firstname }} {{ store.authUser.lastname }}</h4>
            <div class="EnterpriseCardContainer">
                <EnterpriseCard />
            </div>
        </div>
        

        <div v-else-if="currentPage === 'Map'" class="content">
            <!-- Map page content here -->
            <Map />
        </div>
        
        <div v-else-if="currentPage === 'Search'" class="content">
            <h4>Search Page Content</h4>
        </div>

        <div class="footer">
            <FooterUser />
        </div>
    </div>

</template>

<style scoped>
.body {
    display: block;
    width: 100%;
    height: 100%;
    top: 60px;
}

.content {
    margin-top: 60px;
}

.EnterpriseCardContainer {
    display: flex;
    justify-content: center;
    width: 100%;
    padding: 0;
    margin: 0;
}
.menu-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: fixed;
    width: 100%;
    max-height: 80px;
    gap: 10px;
    margin: 20px 0;
    z-index: 1;
}


.search-input {
    flex: 1;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}

.search-input:focus {
    outline: none;
}

.filter-button, .location-button {
    background-color: #f0f0f0;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.filter-button {
    border-left: 1px solid #ddd;
    border-radius: 50%;
    margin-left: 1%;
}

.location-button {
    border-left: 1px solid #ddd;
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
}

.search-container , .switch-container {
    display: flex;
    background-color: #f0f0f0;
    border-radius: 25px;
    margin-top: 10px;
    overflow: hidden;
    width: fit-content;
    min-width: 240px;
    margin-left: auto;
    margin-right: auto;
}

.switch-container button {
    background-color: #f0f0f0;
    border: none;
    color: #004d40;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    flex: 1;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.switch-container button:hover {
    background-color: #e0e0e0;
}

.switch-container button.active {
    background-color: #004d40;
    color: white;
}

.EnterpriseCardContainer {
    display: flex;
    justify-content: center;
    width: 100%;
}

.link {
    text-decoration: none;
}

/* .footer {
    position: sticky;
} */

@media (max-width: 600px) {
    .right {
        padding-left: 10px;
    }
    .menu-container {
        flex-direction: column;
    }

    .search-container {
        width: 100%;
    }

    .switch-container {
        flex-direction: row;
        width: 100%;
    }

    .switch-container button {
        width: 100%;
        margin: 0;
    }
}
</style>