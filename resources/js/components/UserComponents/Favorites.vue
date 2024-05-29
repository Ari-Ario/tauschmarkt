<script setup>
import { useAuthStore } from '../../stores/AuthStore';

import { ref, onMounted } from "vue";
import axios from "axios";

import { RouterLink, useRoute } from "vue-router";

const store = useAuthStore();
let route = useRoute();
let favorites = ref([]);
// const userId = ref(null);
const filteredEnterprises = ref([]);

const loadEnterprises = async () => {
    try {
        const response = await axios.get("/api/enterprises/latest");
        filteredEnterprises.value = response.data.data.filter(enterprise => enterprise.is_seller === 1);
    } catch (error) {
        console.error("Error loading enterprises:", error);
    }
};

// Configure Axios to include credentials
axios.defaults.withCredentials = true;

const loadFavorites = async (userId) => {
    try {
        const response = await axios.get(`api/favorites/${userId}`);
        favorites.value = response.data;
        console.log(favorites.value);
    } catch (error) {
        console.error("Error loading favorites:", error);
    }
};

// Add to favorite
// const addToFavorites = async (enterpriseId) => {
//     try {
//         const response = await axios.post('/api/favorites/add', { first_user_id: enterpriseId , user_id: store.authUser.id});
//         console.log(response.data.message);
//     } catch (error) {
//         console.error('Error adding to favorites:', error);
//     }
// };

// Remove from favorite
// const removeFromFavorites = async (enterprise) => {
//     try {
//         const response = await axios.post('/api/favorites/remove', { first_user_id: enterprise });
//         console.log(response.data.message);
//     } catch (error) {
//         console.error('Error removing from favorites:', error);
//     }
// };

const addOrRemoveFavorites = async (enterprise) => {
    try {
        enterprise.is_favorite = !enterprise.is_favorite;

        await axios.post("/api/enterprises/favorite", {
            first_user_id: enterprise.id,
            is_favorite: enterprise.is_favorite
        });
    } catch (error) {
        console.error("Error adding to favorites:", error);
    }
};

const authStore = useAuthStore()
// userId = 1;
onMounted(() => {
    // loadEnterprises();
    const userId = 1;
    favorites;
    loadFavorites(userId);
});
</script>

<template>
    <div>
        <div class="card" v-for="enterprise in favorites" :key="enterprise.id">
            <div class="Photo">
                <img
                    class="Photo"
                    :src="enterprise.enterprise_picture || '../assets/Placeholder-enterprise.png'"
                    alt=""
                />
                <div class="favorite" @click="addOrRemoveFavorites(enterprise)">
                    <svg v-if="enterprise.is_favorite" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <!-- <svg v-else xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg> -->
                </div>
            </div>
            <div class="details">
                <div class="user-details">
                    <div class="user-photo">
                        <img
                            class="user-photo"
                            v-if="enterprise.profile_picture"
                            :src="enterprise.profile_picture"
                        />
                    </div>
                    <p class="user-name description">
                        {{ enterprise.firstname }} {{ enterprise.lastname }} 
                    </p>
                    <p class="published-on description">
                        {{ enterprise.updated_at }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
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

.details {
    width: 100%;
    padding: 10px;
    position: absolute;
    bottom: 0;
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
    width: 350px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

p {
    margin: 0;
}


.button {
    all: unset;
    width: fit-content;
    background: black;
    border-radius: 50px;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    margin-left: 15px;
    padding: 10px 20px;
}

/* Responsive styles */
@media only screen and (max-width: 600px) {
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
}
</style>