<script setup>
import LogoutButton from '@/components/LogoutButton.vue';
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { useAuthStore } from "../../stores/AuthStore"

const route = useRoute();
const store = useAuthStore();
const modalVisible = ref(false);
</script>

<template>
    <footer v-if="store?.authUser" class="footer">
      <div class="footer-item">
        <router-link :to="{ name: 'map'}" class="link">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>       
      </router-link>
          <span>Betriebsort</span>
      </div>

      <div class="footer-item">
        <router-link :to="{ name: 'addProduct'}" class="link">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
        </router-link>
            <span>Ware </span>
        </div>

        <div class="footer-item">
          <router-link :to="{ name: 'dashboard'}" class="link">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M160-160v-375l-72 55-47-63 439-337 440 336-48 64-392-300-240 184v356h160v80H160Zm540 95q-42 29-92.5 24.5T521-81q-36-36-40.5-86.5T505-260q-29-42-24.5-92.5T521-439q36-36 86.5-40.5T700-455q42-29 92.5-24.5T879-439q36 36 40.5 86.5T895-260q29 42 24.5 92.5T879-81q-36 36-86.5 40.5T700-65Zm0-98 46 32q18 13 39 11t37-18q16-16 18-37t-11-39l-32-46 32-46q13-18 11-39t-18-37q-16-16-37-18t-39 11l-46 32-46-32q-18-13-39-11t-37 18q-16 16-18 37t11 39l32 46-32 46q-13 18-11 39t18 37q16 16 37 18t39-11l46-32Zm0-47q21 0 35.5-14.5T750-260q0-21-14.5-35.5T700-310q-21 0-35.5 14.5T650-260q0 21 14.5 35.5T700-210ZM480-470Zm220 210Z"/></svg>        
          </router-link>
          <span>Hause</span>
        </div>

        <div class="footer-item">
          <router-link :to="{ name: 'OrdersList'}" class="link">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg>
          </router-link>
            <span>Bestellungen </span>
        </div>

      <div class="footer-item">
        <router-link :to="{ name: 'profileInformation'}" class="link">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
        </router-link>
          <span>Profil </span>
      </div>
    </footer>
  </template>               
  
  <script setup>
  </script>
  
  <style scoped>
  .footer {
    display: flex;
    width: 100%;
    justify-content: space-around;
    padding: 0;
    background-color: #e8f0e5ea;
    border-top: 1px solid #e7e7e7;
    position: fixed;
    left: 0;
    bottom: 0;
  }
  
  .footer-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    padding: 10px;
  }
  
  .footer-item span {
    margin-top: 5px;
    font-size: 12px;
    color: #298E46;
  }
  
  .icon {
    width: 24px;
    height: 24px;
    fill: #555;
  }

  .link {
    text-decoration: none;
  }
  </style>
  