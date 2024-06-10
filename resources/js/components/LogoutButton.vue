<script setup>
  import { useAuthStore } from '@/stores/AuthStore';
  import {useRouter} from 'vue-router';

  const router = useRouter();
  const store = useAuthStore();

  const logout = async () => {
      await store.logout().then(() => {
          router.push('/');
      });
  }
</script>

<template>
    <button
    class="button"
      type="button"
      @click="logout"
    >Logout
    </button>
</template>
  
<style scoped>
.button {
    all: unset;
    width: fit-content;
    background: #004d40;
    border-radius: 50px;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    margin-left: 30px;
    padding: 10px 20px;
}
</style>