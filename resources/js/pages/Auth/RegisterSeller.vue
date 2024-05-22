<script setup>
import { useAuthStore } from '@/stores/AuthStore';
import AuthService from "@/services/AuthService";
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import Header from '../../components/header/Header.vue';

// import { createStore } from 'vuex';

import { Axios } from 'axios';

const router = useRouter();
const store = useAuthStore();

const user = ref({
    firstname : '',
    lastname : '',
    street : '',
    house_number : '',
    zip_code : '',
    city : '',
    email : '',
    is_seller : 1,
    password : '',
    password_confirmation : ''
});
function registerUser(){
    // console.log(user.value)
    AuthService.registerUser(user.value)
        .then(() => router.push("/map"))
        .catch((error) => (console.log(error)));
}


</script>

<template>
    <Header />
    
    <!-- Form for registration -->
    <div class="form-container">
        <form @submit.prevent="registerUser" class="register-form">
            <div>
            <router-link to="/">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
            </router-link>
            </div>

            <!-- Name, email, and password inputs -->
            <input name="name" type="text" v-model="user.firstname" placeholder="Vorname" required />
            <input name="name" type="text" v-model="user.lastname" placeholder="Nachname" required />
            <input name="street" type="text" v-model="user.street" placeholder="Strasse" required />
            <input name="HouseNumber" type="text" v-model="user.house_number" placeholder="Hausnummer" required />
            <input name="PLZ" type="text" v-model="user.zip_code" placeholder="PLZ" required />
            <input name="City" type="text" v-model="user.city" placeholder="Ort" required />

            <input name="email" type="email" v-model="user.email" placeholder="Email" required />
            <input name="password" type="password" v-model="user.password" placeholder="Passwort" required />
            <input name="password_confirmation"  type="password"  v-model="user.password_confirmation" placeholder="Passwort wiederholen" required />

            <button type="submit">Register</button>

        </form>
    </div>

</template>

<style scoped>
.form-container {
display: flex;
justify-content: center;
align-items: center;
margin-top: 60px;
}


.register-form {
display: flex;
flex-direction: column;
gap: 20px;
width: 300px;
padding: 20px;
border-radius: 5px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


.register-form input, .register-form button {
padding: 10px;
border-radius: 5px;
border: 1px solid #ccc;
font-size: 16px;
}


.register-form button {
background-color: #007BFF;
color: white;
cursor: pointer;
}
</style>