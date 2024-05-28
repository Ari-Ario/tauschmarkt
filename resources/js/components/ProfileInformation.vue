
<script setup>
import { useAuthStore } from '../stores/AuthStore';
import AuthService from "@/services/AuthService";
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from "vue-router";

const profileItems = ref([]);
const isEditing = ref(false);
const editingItem = ref('');
const editingValue = ref('');
const user = ref({
  firstname: '',
  lastname: '',
  street: '',
  house_number: '',
  city: '',
  zip_code: null,
});

// Fetch user profile data on mount
const store = useAuthStore();
let route = useRoute();
const userId = store?.authUser?.id;

const loadUser = async () => {
    try {
        const response = await axios.get(`api/user/profile/${userId}`);
        user.value = await response.data;
        console.log(user);
    } catch (error) {
        console.error("Error loading blogs:", error);
    }
};

onMounted(() => {
    loadUser();
});

const editItem = (item, key) => {
  editingItem.value = key;
  editingValue.value = item;
  isEditing.value = true;
};

const saveChanges = async () => {
  if (editingItem.value !== null) {
    user.value[editingItem.value] = editingValue.value;
    // console.log({
    //     [editingItem.value]: editingValue.value
    //   })

    // Save changes to the server using Laravel Fortify's API
    try {
      const response = await AuthService.updateUser({
        [editingItem.value]: editingValue.value
      });

      // const response = await axios.put('/api/user/profile-information', {
      //   [editingItem.value]: editingValue.value
      // });
      console.log('Profile updated:', response.data);
    } catch (error) {
      console.error('Error updating profile:', error);
    }
  }
  cancelEdit();
};

const cancelEdit = () => {
  isEditing.value = false;
  editingItem.value = {};
  editingValue.value = '';
};

</script>

<template>
  <div class="container">
    <div class="profile-page">
      <div class="back">
          <router-link to="/dashboard">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
          </router-link>
      </div>
      <h3>Account Details</h3>

      <div class="profile-section" v-for="(item, key)  in user" v-if="user" :key="key">
        <div class="profile-item"  @click="() => editItem(item, key)">
          <span>{{ key }}</span>
          <span class="profile-value" >{{ item }}</span>
        </div>
        <!-- <div v-else style="display: none;"></div> -->
      </div>

      <div v-if="isEditing" class="edit-popup">
        <form @submit.prevent="saveChanges">
          <label :for="editingItem">{{ editingItem }}</label>
          <input type="text" v-model="editingValue" :id="editingItem.key" required />
          <button type="submit" style="margin-bottom: 10px;">Save</button>
          <button type="button" @click="cancelEdit">Cancel</button>
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
}
</style>
