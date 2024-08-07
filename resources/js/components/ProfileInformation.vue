<script setup>
import FooterUser from '../components/footer/FooterUser.vue';
import FooterSeller from '../components/footer/FooterSeller.vue';
import Header from '../components/header/Header.vue';
import { useAuthStore } from '../stores/AuthStore';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from "vue-router";
import authClient from '../services/AuthService';
import { loadStripe } from '@stripe/stripe-js';
import Swal from 'sweetalert2';

const profileItems = ref([]);
const isEditing = ref(false);
const editingItem = ref('');
const editingValue = ref('');
const user = ref({});
const StripeData = ref({});
const locationInfo = ref({});

const frontendKeys = {
  firstname: 'Vorname',
  lastname: 'Nachname',
  email: 'Email',
  street: 'Strasse',
  house_number: 'Hausnummer',
  city: 'Stadt',
  zip_code: 'Postleitzahl',
  opening: 'öffnungszeit',
  closing: 'Betriebsschluss',
  payment: 'Bankkonto',
};

// Fetch user profile data on mount
const store = useAuthStore();
let route = useRoute();
const userId = store?.authUser?.id;

const loadSeller = async () => {
    try {
        const response = await axios.get(`api/user/profile/${userId}`);
        const data = await response.data;
        user.value = {
          Vorname: data.firstname,
          Nachname: data.lastname,
          // Email: data.email,
          Strasse: data.street,
          Hausnummer: data.house_number,
          Stadt: data.city,
          Postleitzahl: data.zip_code,
          öffnungszeit: data.opening,
          Betriebsschluss: data.closing,
          Bankkonto: data.payment,
          // stripe_account_id: data.stripe_account_id,
          // stripe_payment_intent_id: data.stripe_payment_intent_id,
          // add more
        };
        locationInfo.value = {
          latitude: data.latitude,
          longitude: data.longitude,
        };
        StripeData.value = {
          connect: data.connect_id,
          payment_intent: data.payment_intent_id
        }
        // console.log(StripeData.value)
    } catch (error) {
        console.error("Error loading profile:", error);
    }
};

const loadUser = async () => {
    try {
        const response = await axios.get(`api/user/profile/${userId}`);
        const data = await response.data;
        user.value = {
          Vorname: data.firstname,
          Nachname: data.lastname,
          // Email: data.email,
          Strasse: data.street,
          Hausnummer: data.house_number,
          Stadt: data.city,
          Postleitzahl: data.zip_code,
          // öffnungszeit: data.opening,
          // Betriebsschluss: data.closing,
          // Bankkonto: data.payment,
          // add more
        };
    } catch (error) {
        console.error("Error loading profile:", error);
    }
};

onMounted(() => {
    if (store?.authUser?.is_seller) {
      loadSeller();
    } else {
      loadUser();
    }
});

const editItem = (item, key) => {
  editingItem.value = key;
  editingValue.value = item;
  isEditing.value = true;
};

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY);

const saveChanges = async () => {
    if (editingItem.value !== null) {
        const backendKey = Object.keys(frontendKeys).find(
            key => frontendKeys[key] === editingItem.value
        );
        // console.log(StripeData.value.connect, StripeData.value.payment_intent)
        if (backendKey) {
            // Check if payment value has changed
            if (backendKey === 'payment' && user.value[editingItem.value] === editingValue.value && StripeData.value.connect) {
                Swal.fire({
                    icon: 'info',
                    title: 'Keine Änderungen festgestellt',
                    text: 'Der Eingabewert ist derselbe wie der vorhandene Wert.',
                });
                cancelEdit();
                return;
            }

            user.value[editingItem.value] = editingValue.value;

            const payload = Object.fromEntries(
                Object.entries(user.value).map(([key, value]) => [
                    Object.keys(frontendKeys).find(k => frontendKeys[k] === key),
                    value,
                ])
            );

            try {
                const response = await axios.put(`/api/user/update-profile`, payload);
                console.log('Profile updated:', response.data);

                if (backendKey === 'payment') {
                    const onboardResponse = await axios.get('/api/onboard-seller');
                    window.location.href = onboardResponse.data.url;
                }
            } catch (error) {
                console.error('Error updating profile:', error);
            }
        }
    }
    cancelEdit();
};

async function onboardSeller() {
    try {
        const response = await axios.get('/api/onboard-seller');
        window.location.href = response.data.url;
    } catch (error) {
        console.error('Error onboarding seller:', error.response ? error.response.data : error.message);
    }
}

const cancelEdit = () => {
  isEditing.value = false;
  editingItem.value = {};
  editingValue.value = '';
};

const formatTime = (time) => {
  if (time.split(':').length === 2) {
    return `${time}:00`;
  }
  return time;
};
</script>

<template>
  <header>
    <Header />
  </header>
  <div class="container">
    <div class="profile-page">
      <div class="account-details">
        <h3>Meine Profil</h3>
      </div>

      <div class="profile-section" v-for="(value, key) in user" v-if="user" :key="key">
        <div class="profile-item" @click="() => editItem(value, key)">
          <span>{{ key }}</span>
          <span class="profile-value">{{ value }}</span>
        </div>
      </div>

      <div v-if="store?.authUser?.is_seller && !locationInfo.latitude && !locationInfo.longitude" class="profile-item" @click="() => editItem(value, key)">
        
        <router-link :to="{ name: 'map'}" class="link">
          <span>Betriebsort fehlt</span>
        </router-link>

        <router-link :to="{ name: 'map'}" class="link">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
            <path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/>
          </svg>
        </router-link>
      </div>

      <div v-if="store?.authUser?.is_seller">
        <FooterSeller />
      </div>
      <div v-else>
        <FooterUser />
      </div>

      <div v-if="isEditing" class="edit-popup">
        <form @submit.prevent="saveChanges">
          <label :for="editingItem">{{ editingItem }}</label>
          <input
            v-if="editingItem === 'öffnungszeit' || editingItem === 'Betriebsschluss'"
            type="time"
            v-model="editingValue"
            :id="editingItem"
            required
            @change="editingValue = formatTime($event.target.value)"
          />
          <input
            v-else
            type="text"
            v-model="editingValue"
            :id="editingItem"
            required
          />
          <button type="submit" style="margin-bottom: 10px;">Speichern</button>
          <button type="button" @click="cancelEdit">Ablehnen</button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>

.container {
display: flex;
justify-content: center;
align-items: center;
padding-bottom: 68px;
}

.profile-page {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 300px;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.profile-section {
  /* border-bottom: 1px solid #ddd; */
  margin-bottom: 10px;
}

.account-details {
  text-align: center;
  padding: 0%;
  margin: 0%;
}

.profile-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  cursor: pointer;

  background-color: none;
  border: none;
  border-radius: 10px;
  text-align: center;
  cursor: pointer;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

}

.profile-value {
  color: #555;
}

.edit-popup {
  position: fixed;
  top: 30%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 20px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

form {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 10px;
  font-size: 1rem;
  color: #333;
}

input {
  padding: 10px;
  font-size: 1rem;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  padding: 10px 20px;
  font-size: 1rem;
  margin-right: 10px;
  cursor: pointer;
}

button[type="submit"] {
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
}

button[type="button"] {
  background-color: #e0e0e0;
  color: #555;
  border: none;
  border-radius: 4px;
}

@media (max-width: 600px) {

  .profile-page {
    width: 100%;
    padding: 20px;
  }
  .edit-popup {
    position: fixed;
    top: 50%;
  }
  .profile-section {
    /* border-bottom: 1px solid #ddd; */
    margin-bottom: 0;
  }
  .profile-item {
  padding: 9px 0;
  }
}
</style>
