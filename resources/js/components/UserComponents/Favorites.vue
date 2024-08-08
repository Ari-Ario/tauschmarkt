<script setup>
import FooterUser from '../footer/FooterUser.vue';
import Header from '../header/Header.vue';
import { useAuthStore } from '../../stores/AuthStore';
import { authClient } from '../../services/AuthService';

import { ref, onMounted, onActivated } from "vue";
import axios from "axios";

import { RouterLink, useRoute } from "vue-router";

const authStore = useAuthStore()
const store = useAuthStore();
let route = useRoute();
let favorites = ref([]);
const userId = store?.authUser?.id;


const loadFavorites = async (userId) => {
    try {
        const response = await axios.get(`api/favorites/${userId}`);
        favorites.value = response.data;
        // console.log(favorites.value);
    } catch (error) {
        console.error("Error loading favorites:", error);
    }
};


const addOrRemoveFavorites = async ( enterprise) => {
    
    try {
        // console.log( enterprise)
        enterprise.is_favorite = !enterprise.is_favorite;
        if (enterprise.is_favorite === true) {
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
        loadFavorites(userId);

    } catch (error) {
        console.error("Error adding to favorites:", error);
    }
};

onMounted(() => {
    const userId = store.authUser.id;
    favorites;
    loadFavorites(userId);
});

const getEnterprisePicture = (path) => {
  return path ? `storage/${path}` : 'storage/enterprise_images/default.png';
};

const getProfilePicture = (path) => {
  return path ? `storage/${path}` : 'storage/profile_images/default.png';
};
</script>

<template>
    <header>
        <Header />
    </header>

    <div v-if="favorites" v-for="enterprise in favorites" :key="favorites.id" class="container">
        <div v-if="enterprise.is_seller" class="card">
            <div class="Photo">
                <img
                    class="Photo"
                    :src="getEnterprisePicture(enterprise.enterprise_picture)"
                    alt=""
                />
                <div class="favorite" @click="addOrRemoveFavorites( enterprise)">
                    <svg v-if="enterprise.is_favorite" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
            <div class="details">
                <div class="user-details">
                    <div class="user-photo">
                        <img
                            class="user-photo"
                            :src="getProfilePicture(enterprise.profile_picture)"
                        />
                    </div>
                    <p class="user-name description">
                        {{ enterprise.firstname }} {{ enterprise.lastname }} 
                    </p>
                    <p class="published-on description">
                        {{ enterprise.updated_at }}
                    </p>
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
        <div>
            <FooterUser />
        </div>
    </div>
    <div v-if="!favorites.length" class="container">
        <h3>Sie haben noch keine Favoriten hinzugefügt.</h3>
        <div>
            <FooterUser />
        </div>
    </div>
</template>

<style scoped>
.container{
    display: flex;
    flex-direction: column;
    /* justify-content: center; */
    align-items: center;
    margin-top: 34px;
}
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
    height: 80%;
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