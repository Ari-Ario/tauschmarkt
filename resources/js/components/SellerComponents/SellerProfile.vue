<script setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../../stores/AuthStore';
import axios from 'axios';
import Swal from 'sweetalert2';
import { WebCamUI } from 'vue-camera-lib';
const store = storeToRefs(useAuthStore());
const seller = ref({
  enterprise_picture: null,
  profile_picture: null,
});

const defaultImage = '/path/to/default/image.jpg';
const webCamVisible = ref(false);
const webCamVisibleProfile = ref(false);

const backgroundImageInput = ref(null);
const profileImageInput = ref(null);

const changeBackgroundImage = () => {
  backgroundImageInput.value.click();
};

const onBackgroundImageChange = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const imageUrl = URL.createObjectURL(file);
  seller.value.enterprise_picture = imageUrl;

  const formData = new FormData();
  formData.append('enterprise_picture', file);

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  try {
    const response = await axios.post('/api/bg-picture', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-CSRF-TOKEN': csrfToken,
      },
    });

    console.log('Image uploaded successfully:', response.data);
    Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            title: 'Poto wurde hizugefügt!',
        });
  } catch (error) {
    console.error('Error uploading image:', error);
    Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Ups. Fehler, versuchen Sie wieder!'
      });
  }
};

const changeProfileImage = () => {
  profileImageInput.value.click();
};

const onProfileImageChange = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const imageUrl = URL.createObjectURL(file);
  seller.value.profile_picture = imageUrl;

  const formData = new FormData();
  formData.append('profile_picture', file);

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  try {
    const response = await axios.post('/api/profile-picture', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-CSRF-TOKEN': csrfToken,
      },
    });

    console.log('Image uploaded successfully:', response.data);
    Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            title: 'Foto wurde hizugefügt!',
        });
  } catch (error) {
    console.error('Error uploading image:', error);
    Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          title: 'Ups. Fehler, versuchen Sie wieder!'
      });
  }
};

const showWebCam = () => {
  webCamVisible.value = !webCamVisible.value;
};
const hideWebCam = () => {
  webCamVisible.value = false;
};

const showWebCamProfile = () => {
  webCamVisibleProfile.value = !webCamVisibleProfile.value;
};
const hideWebCamProfile = () => {
  webCamVisibleProfile.value = false;
};

const photoTaken = (data) => {
  const file = new File([data.blob], 'photo.jpg', { type: 'image/jpeg' });
  const fakeEvent = {
    target: {
      files: [file],
    },
  };
  handleFileUpload(fakeEvent);
  webCamVisible.value = false;
};

const photoTakenProfile = (data) => {
  const file = new File([data.blob], 'photo.jpg', { type: 'image/jpeg' });
  const fakeEvent = {
    target: {
      files: [file],
    },
  };
  handleProfileUpload(fakeEvent);
  webCamVisibleProfile.value = false;
};

const handleFileUpload = (event) => {
  onBackgroundImageChange(event);
};

const handleProfileUpload = (event) => {
  onProfileImageChange(event);
};


const sellerId = store?.authUser?.value.id;
const loadUser = async () => {
    try {
        const response = await axios.get(`/api/user/profile/${sellerId}`);
        seller.value = response.data;

        console.log(seller.value);
    } catch (error) {
        console.error("Error loading user profile:", error);
    }
};

onMounted(() => {
    loadUser();
});
</script>

<template>
  <div class="seller-profile">
    <input type="file" ref="profileImageInput" @change="onProfileImageChange" style="display: none;" />
    <input type="file" ref="backgroundImageInput" @change="onBackgroundImageChange" style="display: none;" />

    <div class="background-container">
      <WebCamUI v-if="webCamVisible" :fullscreenState="false" @photoTaken="photoTaken" ref="webCam" class="webCam" />
      <img :src="seller.enterprise_picture || defaultImage" alt="Background Image" class="background-image" />

      <div class="camera-icons bg-camera-icons">
        <div class="camera-icon bg-camera" @click="changeBackgroundImage">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M160-80v-80h640v80H160Zm200-160v-280H200l280-360 280 360H600v280H360Zm80-80h80v-280h76L480-750 364-600h76v280Zm40-280Z"/></svg>
        </div>
        <div class="camera-icon bg-camera cam" @click="showWebCam">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
            <path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/>
          </svg>
        </div>
      </div>

      <WebCamUI v-if="webCamVisibleProfile" :fullscreenState="false" @photoTaken="photoTakenProfile" ref="webCamProfile" class="webCamProfile" />
      <div class="overlay">
        <img :src="seller.profile_picture" alt="Profile Image" class="profile_picture" />

        <div class="camera-icons profile-camera-icons">
          <div class="camera-icon profile-camera" @click="changeProfileImage">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M160-80v-80h640v80H160Zm200-160v-280H200l280-360 280 360H600v280H360Zm80-80h80v-280h76L480-750 364-600h76v280Zm40-280Z"/></svg>
          </div>
          <div class="camera-icon profile-camera cam" @click="showWebCamProfile">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
              <path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/>
            </svg>
        </div>
      </div>
    </div>
  </div>

    
    <h2>{{ seller.firstname }} {{ seller.lastname }}</h2>
    
    <div v-if="!seller.latitude && !seller.longitude" class="footer-item">
      <span>Betriebsort fehlt</span>
      <router-link :to="{ name: 'map'}" class="link">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
          <path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/>
        </svg>
      </router-link>
    </div>
    
  </div>
</template>

<style scoped>
.seller-profile {
  text-align: center;
  margin-bottom: 20px;
  position: relative;
}

.background-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  width: 100%;
  min-width: 300px;
  height: 300px;
  overflow: hidden;
}

.background-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.camera-icons {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: absolute;
  /* background-color: rgba(0, 0, 0, 0.6); */
  /* border-radius: 50%; */
  padding: 5px;
  cursor: pointer;
}

.bg-camera-icons {
  top: 20px;
  right: 20px;
}

.profile-camera-icons {
  bottom: 10px;
  right: 0px;
}

.camera-icon + .camera-icon {
  margin-top: 10px;
}
.profile-camera, .bg-camera {
  border-radius: 40%;
  background-color: rgba(0, 0, 0, 0.397);
}


.overlay {
  position: absolute;
  bottom: 2px;
  left: 25%;
  transform: translateX(-50%);
  border: 3px solid white;
  border-radius: 50%;
  background-color: #adacac;
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
  margin-top: 20px; /* Adjust this to ensure the text is below the profile image */
  font-size: 24px;
  font-weight: bold;
}

p {
  font-size: 16px;
  color: #666;
}

/* Style for WebCam elements */
.webCam {
  width: 100%;
  max-width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1000; /* Ensure webcam elements are on top */
  height: min-content;
}
.webCamProfile {
  width: 100%;
  max-width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1010; /* Ensure webcam elements are on top */
  height: min-content;
}
/* Active state styling */
.webCam.active, .webCamProfile.active {
  opacity: 1;
  z-index: 1000; /* Ensure webcam elements are on top */
}

/* Blur background when webcam is active */
.seller-profile.webCamActive .background-container,
.seller-profile.webCamProfile-active .overlay {
  opacity: 0.3;
  pointer-events: none;
}

/* Hide the SVGs in background-container when WebCamUI is active */
.seller-profile.webCamActive .background-container .bg-camera-icons,
.seller-profile.webCamProfile-active .background-container .bg-camera-icons {
  display: none;
}

/* Show the SVGs in the overlay when WebCamUI is active */
.seller-profile.webCamActive .overlay .profile-camera-icons,
.seller-profile.webCamProfile-active .overlay .profile-camera-icons {
  display: flex;
}

/* Ensure the WebCamProfile covers the overlay */
.seller-profile.webCamProfile-active .overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
  z-index: 999; /* Ensure it is behind the WebCamUI */
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Full width for WebCamUI */
.webCam, .webCamProfile {
  width: 100%;
  height: 100%;
  max-width: none;
}

/* Make the WebCamUI cover the entire overlay */
.webCam.active, .webCamProfile.active {
  display: block;
  opacity: 1;
  z-index: 1000; /* Ensure webcam elements are on top */
}

/* Ensure that the overlay is positioned correctly */
.seller-profile.webCamActive .background-container,
.seller-profile.webCamProfile-active .overlay {
  opacity: 0.3;
  pointer-events: none;
}

/* Camera icons styling */
.camera-icons svg {
  fill: #298E46;
}

.bg-camera, .profile-camera {
  z-index: 1010;
  opacity: 1;
}

.bg-camera {
  top: 20px;
  right: 20px;
}

.profile-camera {
  bottom: 10px;
  right: 10px;
}

/* Style for mobile view */
@media (max-width: 600px) {
  .webCam, .webCamProfile {
    height: 100vh; /* Full viewport height for mobile */
    max-height: none;
  }

  .seller-profile.webCamProfile-active .overlay,
  .seller-profile.webCamActive .background-container {
    height: 100vh; /* Full viewport height for mobile */
  }

  .seller-profile.webCamProfile-active .overlay,
  .seller-profile.webCamActive .background-container {
    overflow: hidden;
  }
}

@media (max-width: 1025px) {

  .cam {
    display: none;
  }
}


@media (max-width: 600px) {
  /* .background-container {
    height: 200px;
  }

  .overlay {
    width: 80px;
    height: 80px;
    bottom: 2px;
  } */

  h2 {
    margin-top: 20px;
    font-size: 20px;
  }

  p {
    font-size: 14px;
  }
}
</style>

