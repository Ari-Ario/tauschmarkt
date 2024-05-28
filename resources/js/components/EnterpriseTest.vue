<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

import CategoryTags from "./CategoryTags.vue";
import { RouterLink } from "vue-router";

const filteredEnterprises = ref([]);
// const enterprises = ref([]);

const loadenterprises = async () => {
    try {
        const response = await axios.get("/api/enterprises/latest");
        // enterprises.value = response.data;
        filteredEnterprises.value = response.data.data.filter(enterprise => enterprise.is_seller === 1);
        // console.log(filteredEnterprises.value);
    } catch (error) {
        console.error("Error loading enterprises:", error);
    }
};

onMounted(() => {
    loadenterprises();
});

</script>

<template>

    <div>
        <div class="card" v-for="enterprise in filteredEnterprises" :key="enterprise.id">

            <div class="Photo">
                <img
                    class="Photo"
                    :src="enterprise.enterprise_picture || '../assets/Placeholder-enterprise.png'"
                    alt=""
                />

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
                            {{ enterprise.firstname }}
                            {{ enterprise.lastname }} 
                        </p>
                        <p  class="published-on description">
                            {{ enterprise.updated_at }}
                        </p>
                    </div>

                </div>

                <!-- <router-link
                    class="details-link"
                    :to="{ name: 'enterprisedetail', params: { id: enterprise.id } }"
                    >View Details</router-link
                >
                <div class="buttons">
                    <router-link
                        class="button"
                        :to="{ name: 'editenterprise', params: { id: enterprise.id } }"
                        >Edit</router-link
                    >
                    <a class="button" @click="deleteenterprise(enterprise.id)"> Delete </a>
                </div> -->

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