<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '../stores/AuthStore';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { GoogleMap, Marker, Circle } from 'vue3-google-map';
import { location, distance, setLocation, setDistance } from '../locals';

const store = useAuthStore();
const router = useRouter();

const userLocation = ref(null);
const center = ref({ lat: 46.938749674988486, lng: 7.459564360522899 });
const radius = ref(10); // Default radius in km
const city = ref('');
const mapRef = ref(null); // Reference to the Google Map instance
const enterprises = ref([]);

// Fetch the API key from the environment variables
const apiKey = import.meta.env.VUE_APP_GOOGLE_MAP_API_KEY;

const circleOptions = ref({
  center: center.value,
  radius: radius.value * 1000, // Convert km to meters
  fillColor: '#0088ff',
  fillOpacity: 0.2,
  strokeColor: '#0088ff',
  strokeOpacity: 0.5,
  strokeWeight: 2,
});

// Watch for changes in center and radius to update the circle options
watch([center, radius], () => {
  circleOptions.value = {
    ...circleOptions.value,
    center: center.value,
    radius: radius.value * 1000,
  };
  fitMapToBounds();
});

const updateCircle = () => {
  circleOptions.value = {
    ...circleOptions.value,
    radius: radius.value * 1000,
  };
  fitMapToBounds();
};

const updateBackend = async () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  try {
    const response = await axios.put(`/api/user/update-location`, location.value);
    console.log('Location updated:', response.data);
  } catch (error) {
    console.error('Error updating profile:', error);
  }
};

const useCurrentLocation = async () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async (position) => {
      location.value = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
      };
      center.value = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      updateCircle();
      await updateBackend();
      await loadEnterprises(location.value.latitude, location.value.longitude, radius.value, store.authUser.id);
    });
  } else {
    alert('Geolocation is not supported by this browser.');
  }
};

const savePlace = () => {
  updateBackend();
};

const searchCity = (place) => {
  if (place.geometry) {
    center.value = {
      lat: place.geometry.location.lat(),
      lng: place.geometry.location.lng(),
    };
    location.value = {
      latitude: place.geometry.location.lat(),
      longitude: place.geometry.location.lng(),
    };
    updateCircle();

    const addressComponents = place.address_components;
    let city = '';

    for (const component of addressComponents) {
      const types = component.types;
      if (types.includes('locality')) {
        city = component.long_name;
        break;
      }
    }

    console.log(`City: ${city}`);
  } else {
    console.error('Place has no geometry');
  }
};

const showResults = () => {
  alert('Show results within ' + radius.value + ' km');
};

const fitMapToBounds = () => {
  if (mapRef.value) {
    const bounds = new google.maps.LatLngBounds();
    const circleCenter = new google.maps.LatLng(center.value.lat, center.value.lng);
    const radiusInMeters = radius.value * 1000;

    // Calculate the bounds of the circle
    const northEast = google.maps.geometry.spherical.computeOffset(circleCenter, radiusInMeters, 45); // 45 degrees is NE
    const southWest = google.maps.geometry.spherical.computeOffset(circleCenter, radiusInMeters, 225); // 225 degrees is SW

    bounds.extend(circleCenter);
    bounds.extend(northEast);
    bounds.extend(southWest);

    mapRef.value.fitBounds(bounds);
  }
};

const handleMapClick = (event) => {
  center.value = {
    lat: event.latLng.lat(),
    lng: event.latLng.lng(),
  };
  location.value = {
    latitude: event.latLng.lat(),
    longitude: event.latLng.lng(),
  };
  updateCircle();
};

const loadEnterprises = async (latitude, longitude, distance, userId) => {
  try {
    const response = await axios.get(`/api/enterprises/${userId}`, {
      params: {
        latitude,
        longitude,
        distance,
      },
    });
    enterprises.value = response.data;
  } catch (error) {
    console.error("Error loading enterprises:", error);
  }
};

const myLocation = onMounted(async () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async (position) => {
      userLocation.value = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      center.value = userLocation.value;
      location.value = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
      };
      await loadEnterprises(location.value.latitude, location.value.longitude, radius.value, store.authUser.id);
    });
  } else {
    console.error('Geolocation is not supported by this browser.');
  }
});

const refreshPage = () => {
  location.reload(); // Reloads the current page
};

const autocomplete = ref(null);

const initAutocomplete = () => {
  if (autocomplete.value) return; // Avoid initializing again

  const input = document.getElementById('autocomplete');
  autocomplete.value = new google.maps.places.Autocomplete(input, {
    types: ['(cities)'],
  });

  autocomplete.value.addListener('place_changed', () => {
    const place = autocomplete.value.getPlace();
    if (place.geometry) {
      const lat = place.geometry.location.lat();
      const lng = place.geometry.location.lng();
      setLocation(lat, lng);
      console.log(`City: ${place.formatted_address}`);
    } else {
      console.error('Place has no geometry');
    }
  });
};

const openEnterprise = (enterpriseId) => {
  router.push({ name: 'index', params: { id: enterpriseId } });
};

const goToDashboard = () => {
  setLocation(location.value.latitude, location.value.longitude);
  setDistance(radius.value);
  router.push({ name: 'dashboard' });
};
</script>

<template>
  <router-link :to="{ name: 'dashboard'}" class="link">
    <div class="back">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46">
        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
      </svg>
    </div>
  </router-link>

  <div class="map-container">
    <GoogleMap
      :api-key="apiKey"
      :center="center"
      :zoom="10"
      style="width: 100%; height: 100%"
      @load="mapRef.value = $event"
      @click="handleMapClick"
    >
      <!-- Render small circles for enterprises -->
      <Circle
        v-for="enterprise in enterprises"
        :key="enterprise.id"
        :options="{
          center: { lat: parseFloat(enterprise.latitude), lng: parseFloat(enterprise.longitude) },
          radius: 500,
          fillColor: '#FF0000',
          fillOpacity: 0.5,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
        }"
        @click="() => openEnterprise(enterprise.id)"
      />
      <Circle v-if="!store.authUser.is_seller" :options="circleOptions" />
      <Marker :options="{ position: center }" />
    </GoogleMap>

    <form @submit.prevent="myLocation" class="location-form">
      <div v-if="store.authUser.is_seller" class="controls">
        <h4>Auf Ihren Hof klicken</h4>
        <button v-if="store.authUser.is_seller" @click="savePlace">Ort speichern</button>
        <button @click="useCurrentLocation">Meinen Standort verwenden</button>
      </div>

      <div v-else class="controls">
        <input 
          id="autocomplete" 
          type="text" 
          placeholder="Enter a city or location" 
          @focus="initAutocomplete"
        />
        <button @click="searchCity">Stadt suchen</button>
        <h4>Distanz wählen</h4>
        <div class="slider-container">
          <input type="range" min="1" max="20" v-model="radius" @input="updateCircle" />
          <span>{{ radius }} km</span>
        </div>
        <button @click="goToDashboard">Suchen</button>
      </div>
    </form>
  </div>
</template>


<style scoped>
.back {
  position: fixed;
  top: 2%;
  left: 2%;
  padding: 5px 0 0 5px;
  text-align: center;
  align-items: center;
  z-index: 2;
  max-width: max-content;
  background-color: #ffffff7c;
}
html, body, #app, .map-container {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
  z-index: 0;
}

.map-container {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.controls {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  background: white;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: calc(100% - 20px);
  max-width: 400px;
  z-index: 2;
}

input[type="text"] {
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 3px;
  width: 96.5%;
}

button {
  padding: 5px 10px;
  border: none;
  background: #0088ff;
  color: white;
  border-radius: 3px;
  cursor: pointer;
}

button:hover {
  background: #005bb5;
}

.controls h4 {
  text-align: center;
  padding-bottom: 0px;
  margin-bottom: 0px;
}

.slider-container {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.slider-container input[type="range"] {
  flex: 1;
}
</style>
