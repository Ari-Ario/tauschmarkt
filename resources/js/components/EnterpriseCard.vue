<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const enterprises = ref([]);
let filteredEnterprises = [];

const loadenterprises = async () => {
    try {
        const response = await axios.get("/api/enterprises/latest");
        enterprises.value = response.data;
        filteredEnterprises = enterprises.value.data.filter(enterprise => enterprise.is_seller === 1);
        // console.log(filteredEnterprises);
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
        <div class="card" v-for="enterprise in filteredEnterprises" :key="filteredEnterprises.id">
            <div class="Photo">
                <img
                    class="Photo"
                    :src="enterprise.enterprise_picture || '../assets/Placeholder-enterprise.png'"
                    alt=""
                />
            </div>
            <div class="details">
                <!-- <div class="title" v-if="enterprise">
                    {{ enterprise.id }}
                </div>
                <p v-if="enterprise" class="description">
                    {{ truncate(enterprise.description, 200) }}
                </p> -->
                <div class="user-details">
                    <div class="user-photo">
                        <img
                            class="user-photo"
                            v-if="enterprise.profile_picture"
                            :src="enterprise.profile_picture"
                        />
                    </div>
                    <p v-if="enterprise" class="user-name description">
                        {{ enterprise.firstname }}
                        {{ enterprise.lastname }} 
                    </p>
                    <p v-if="enterprise" class="published-on description">
                        {{ enterprise.updated_at }}
                    </p>
                </div>
               
                <!-- <router-link class="details-link" :to="{ name: 'enterprisedetail', params: { id: enterprise.id } }">View Details</router-link>
                <div class="buttons">
                    <a class="button" href="">
                        <router-link class="details-link" :to="{ name: 'editenterprise', params: { id: enterprise.id } }">Edit</router-link>
                    </a>
                    <a class="button" href=""> Delete</a>
                </div> -->
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Existing styles */
.card {
    margin: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    background-color: #ffffff;
    max-width: 1200px;
    min-height: 300px;
    border: 1px solid rgba(0, 0, 0, 0.523);
}

.Photo {
    width: 80%;
    height: 100%;
    left: 0;
    padding-top: 0;
}

.details {
    height: fit-content;
    display: flex;
    flex-direction: column;
    color: white;
    justify-content: space-between;
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

.read-more {
    text-decoration: none;
}

.title {
    font-size: 24px;
    font-weight: 400;
    color: #000000;
}

.description {
    font-size: 16px;
    font-weight: 200;
    color: #000000;
}

p {
    margin: 0;
}

.button {
    width: 100%;
    height: 30px;
    background: black;
    border-radius: 50px;
    padding: 8px;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-align: center;
    line-height: 30px;
    text-decoration: none;
    margin-left: 15px;
    padding-left: 20px;
    padding-right: 20px;
}

/* Responsive styles */
@media only screen and (max-width: 600px) {
    .card {
        flex-direction: column; /* Change flex direction to column */
        align-items: center;
        width: 90%;
        height: fit-content;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .Photo {
        display: flex; /* Hide the Photo class */
        width: 100%;
        left: 0;
    }

    .details {
        width: 100%;
        text-align: left;
    }

    .user-details {
        width: 90%;
        align-items: center;
    }

    .button {
        margin-top: 30px;
        width: 50%; /* Adjust button width */
    }
    .read-more {
        font-size: 15px;
        text-align: right;
        padding-right: 10px;
    }
}
</style>