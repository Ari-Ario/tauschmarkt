<template>
    <div class="verify-email-container">
      <h2>Bitte überprüfen Sie Ihre E-Mail</h2>
      <p>Eine Bestätigungs-E-Mail wurde an Ihre E-Mail-Adresse gesendet. Bitte überprüfen Sie Ihren Posteingang und klicken Sie auf den Bestätigungslink.</p>
      <button class="button" @click="resendVerificationEmail">Bestätigungsmail erneut senden</button>
    </div>
</template>
  
<script setup>
  import { ref } from 'vue';
  import AuthService from '@/services/AuthService';
    import { useAuthStore } from '@/stores/AuthStore';
    import Swal from 'sweetalert2';
  
  const message = ref('');
  
  const resendVerificationEmail = async () => {
    try {
      await AuthService.sendVerification({});
      message.value = 'A new verification email has been sent!';
      Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            title: 'Eine neue Bestätigungs-E-Mail wurde gesendet!',
        });
    } catch (error) {
      console.error(error);
      message.value = 'Failed to resend verification email. Please try again later.';
      Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Ups. Fehler, versuchen Sie wieder!'
      });
      if (error.response.status === 401) {
      router.push('/login'); // Ensure /login route exists
    }
    }
  };
</script>
  
<style scoped>
  .verify-email-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }

  .button {
    background-color: #298E46;
    color: white;
    font-weight: bold;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    }
  </style>
  