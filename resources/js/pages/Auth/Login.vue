<script setup>
import { useAuthStore } from '@/stores/AuthStore';
import AuthService from "@/services/AuthService";
import {ref} from 'vue';
import {useRouter} from 'vue-router';
// import BlogHeader from '../components/BlogHeader.vue';  
// import Navbar from '@/components/Navbar.vue';
// import { createStore } from 'vuex';

const router = useRouter();
const store = useAuthStore();

const user = ref({
    email : '',
    password : ''
});


const login = async() => {

    try {

        await AuthService.login(user.value);

        const authUser = await store.getAuthUser();

        if(authUser) {
          router.push("/dashboard");
        }
        else {
            console.log('error');
        }
      } catch (error) {
        console.log(error);
      }
}

</script>

<template>
    <BlogHeader/>
    <!-- Form for login -->
    <div class="form-container">
        <form @submit.prevent="login" class="login-form">
            <div>
                <router-link to="/"><--</router-link>
            </div>

            <!-- Email and password inputs -->
            <input type="email" v-model="user.email" placeholder="Email" required />
            <input type="password" v-model="user.password" placeholder="Password" required />
            <!-- Submit button -->
            <button type="submit">Login</button>

        </form>
    </div>
</template>

<style scoped>
.form-container {
display: flex;
justify-content: center;
align-items: center;
}


.login-form {
display: flex;
flex-direction: column;
gap: 20px;
width: 300px;
padding: 20px;
border-radius: 5px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


.login-form input, .login-form button {
padding: 10px;
border-radius: 5px;
border: 1px solid #ccc;
font-size: 16px;
}


.login-form button {
background-color: #007BFF;
color: white;
cursor: pointer;
}
</style>