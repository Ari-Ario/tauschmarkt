<script setup>
import { ref } from 'vue';
import AuthService from "@/services/AuthService";
import Swal from 'sweetalert2';

const email = ref('');

const sendResetPasswordLink = async () => {
  try {
    await AuthService.forgotPassword({ email: email.value });
    Swal.fire({
      icon: 'success',
      title: 'Link zum Zurücksetzen des Passworts gesendet!',
      text: 'Bitte überprüfen Sie Ihre E-Mails auf den Link zum Zurücksetzen.',
    });
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Fehler!',
      text: 'Der Link zum Zurücksetzen des Passworts konnte nicht gesendet werden. Bitte versuche es erneut.',
    });
  }
};
</script>

<template>
  <div class="forgot-password-container">
    <div class="reset-password-form">
      <div>
          <router-link to="login">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
          </router-link>
      </div>
      <h2>Passwort vergessen</h2>
      <input type="email" v-model="email" placeholder="Ihre E-Mail Adresse" required />
      <button @click="sendResetPasswordLink">Link zum Zurücksetzen senden</button>
    </div>
  </div>
</template>

<style scoped>
.forgot-password-container {
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
.forgot-password-container input {
    width: calc(100% - 20px);
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #333;
}

.forgot-password-container button {
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
.forgot-password-container button {
  background-color: #298E46;
  color: white;
  cursor: pointer;
}
</style>
