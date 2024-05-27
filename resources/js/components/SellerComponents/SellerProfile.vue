<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/AuthStore';

const store = useAuthStore();
const seller = ref({
  firstname: store.authUser.firstname,
  lastname: store.authUser.lastname,
  is_seller: store.authUser.is_seller,
  profile_picture: 'path/to/profile_picture.jpg',
  enterprise_picture: 'path/to/background-image.jpg',
  bio: 'Experienced seller with a wide range of products.'
});

const profileImageInput = ref(null);
const backgroundImageInput = ref(null);

function changeProfileImage() {
  profileImageInput.value.click();
}

function changeBackgroundImage() {
  backgroundImageInput.value.click();
}

function onProfileImageChange(event) {
  const file = event.target.files[0];
  if (file) {
    // Implement the file upload logic here
    const reader = new FileReader();
    reader.onload = (e) => {
      seller.value.profileImage = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function onBackgroundImageChange(event) {
  const file = event.target.files[0];
  if (file) {
    // Implement the file upload logic here
    const reader = new FileReader();
    reader.onload = (e) => {
      seller.value.backgroundImage = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}
</script>

<template>
  <div class="seller-profile">
    <input type="file" ref="profileImageInput" @change="onProfileImageChange" style="display: none;" />
    <input type="file" ref="backgroundImageInput" @change="onBackgroundImageChange" style="display: none;" />
    
    <div class="background-container">
      <img :src="seller.enterprise_picture" alt="Background Image" class="background-image" />
      <div class="camera-icon bg-camera" @click="changeBackgroundImage">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg>
      </div>
      <div class="overlay">
        <img :src="seller.profile_picture" alt="Profile Image" class="profile_picture" />
        <div class="camera-icon profile-camera" @click="changeProfileImage">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg>
        </div>
      </div>
    </div>
    <h2>{{ seller.firstname }} {{ seller.lastname }}</h2>
    <p>{{ seller.bio }}</p>
  </div>
</template>

<style scoped>
.seller-profile {
  text-align: center;
  margin-bottom: 20px;
  position: relative;
}

.background-container {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.background-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.camera-icon {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background-color: rgba(0, 0, 0, 0.6);
  border-radius: 50%;
  padding: 5px;
  cursor: pointer;
}

.overlay {
  position: absolute;
  bottom: -50px;
  left: 50%;
  transform: translateX(-50%);
  border: 3px solid white;
  border-radius: 50%;
  width: 100px;
  height: 100px;
  overflow: hidden;
}

.profile_picture {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

h2 {
  margin-top: 60px; /* Adjust this to ensure the text is below the profile image */
  font-size: 24px;
  font-weight: bold;
}

p {
  font-size: 16px;
  color: #666;
}

@media (max-width: 600px) {
  .background-container {
    height: 150px;
  }

  .overlay {
    width: 80px;
    height: 80px;
    bottom: -40px;
  }

  h2 {
    margin-top: 50px;
    font-size: 20px;
  }

  p {
    font-size: 14px;
  }
}
</style>
