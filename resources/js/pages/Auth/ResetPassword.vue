<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AuthService from "@/services/AuthService";

const route = useRoute();
const router = useRouter();
const token = ref(route.query.token || '');
const email = ref(route.query.email || '');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref('');
const success = ref('');

const resetPassword = async () => {
//   console.log(token.value)
    if (password.value !== passwordConfirmation.value) {
        error.value = 'Passwörter stimmen nicht überein';
        return;
    }

    try {
        const payload = {
            token: token.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value
        };
        await AuthService.resetPassword(payload);
        success.value = 'Passwort erfolgreich zurückgesetzt';
        setTimeout(() => {
            router.push('/'); // redirect to home or login after reset
        }, 2000);
    } catch (err) {
        console.error(err);
        error.value = 'Passwort konnte nicht zurückgesetzt werden. Bitte versuche es erneut.';
    }
};
</script>

<template>
    <div class="reset-password-container">
        <div class="reset-password-form">
            <h1>Passwort zurücksetzen</h1>
            <form @submit.prevent="resetPassword">
                <input 
                    type="email" 
                    v-model="email" 
                    placeholder="Email" 
                    required
                    readonly
                />
                <input 
                    type="password" 
                    v-model="password" 
                    placeholder="Neues Kennwort" 
                    required 
                />
                <input 
                    type="password" 
                    v-model="passwordConfirmation" 
                    placeholder="Bestätige neues Passwort" 
                    required 
                />
                <button type="submit">Passwort zurücksetzen</button>
                <div v-if="error" class="error-message">{{ error }}</div>
                <div v-if="success" class="success-message">{{ success }}</div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.reset-password-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f5f5f5;
}

.reset-password-form {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    width: 100%;
    max-width: 400px;
    margin: 1rem;
}

.reset-password-form h1 {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.5rem;
    text-align: center;
}

.reset-password-form input {
    width: calc(100% - 20px);
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #333;
}

.reset-password-form button {
    width: 100%;
    padding: 0.75rem;
    background-color: #298E46;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.reset-password-form button:hover {
    background-color: #247c3e;
}

.reset-password-form .error-message {
    color: #d9534f;
    font-size: 0.875rem;
    margin-top: 1rem;
}

.reset-password-form .success-message {
    color: #5bc0de;
    font-size: 0.875rem;
    margin-top: 1rem;
}
</style>
