<script setup>
    import { ref, onMounted, watch } from 'vue';

    import { useAuthStore } from '../stores/AuthStore';
    import LogoutButton from '@/components/LogoutButton.vue';
    import FooterUser from '../components/footer/FooterUser.vue';
    import EnterpriseCard from "../components/EnterpriseCard.vue";
    import Map from "./Map.vue";


    const store = useAuthStore();

    const currentPage = ref('Liste');
    function switchPage(page) {
        currentPage.value = page;
    }
</script>


<template>
    <header>
        <LogoutButton />
    </header>
    <div v-if="!store.authUser.is_seller" class="content">
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
                <button 
                    :class="{ active: currentPage === 'Map' }" 
                    @click="switchPage('Map')">Karte 📍
                </button>
            </div>
        </div>
        
        <div v-if="currentPage === 'Liste'" class="content">
            <h4 v-if="store.authUser">Hallo {{ store.authUser.firstname }} {{ store.authUser.lastname }} {{ store.authUser.is_seller }}</h4>
            <div class="EnterpriseCardContainer">
                <EnterpriseCard />
            </div>
        </div>

        <div v-else-if="currentPage === 'Map'" class="content">
            <!-- Map page content here -->
            <Map />
            <h4>Map Page Content</h4>
        </div>
        
        <div v-else-if="currentPage === 'Search'" class="content">
            <h4>Search Page Content</h4>
        </div>

        <div>
            <FooterUser />
        </div>
    </div>

    <div v-if="store.authUser.is_seller" class="content">
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
                <button 
                    :class="{ active: currentPage === 'Map' }" 
                    @click="switchPage('Map')">Karte 📍
                </button>
            </div>
        </div>
        
        <div v-if="currentPage === 'Liste'" class="content">
            <h4 v-if="store.authUser">Hallo {{ store.authUser.firstname }} {{ store.authUser.lastname }} {{ store.authUser.is_seller }}</h4>
            <div class="EnterpriseCardContainer">
                <EnterpriseCard />
            </div>
        </div>

        <div v-else-if="currentPage === 'Map'" class="content">
            <!-- Map page content here -->
            <Map />
            <h4>Map Page Content</h4>
        </div>
        
        <div v-else-if="currentPage === 'Search'" class="content">
            <h4>Search Page Content</h4>
        </div>

        <div>
            <FooterSeller />
        </div>
    </div>
</template>

<style scoped>
.body {
    display: block;
    width: 100%;
    height: 100%;
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

/* .search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 600px;
    background-color: #fff;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
} */

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

@media (max-width: 600px) {
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