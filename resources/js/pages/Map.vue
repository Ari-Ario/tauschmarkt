<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '../stores/AuthStore';

import { GoogleMap, Marker, Circle } from 'vue3-google-map';
import VueGoogleAutocomplete from 'vue-google-autocomplete';

const store = useAuthStore();

const userLocation = ref(null);

const center = ref({ lat: 46.938749674988486, lng: 7.459564360522899 });
const radius = ref(10); // Default radius in km
const city = ref('');
const mapRef = ref(null); // Reference to the Google Map instance

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

const useCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      center.value = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      console.log(center.value);
      updateCircle();
    });
  } else {
    alert('Geolocation is not supported by this browser.');
  }
};

const searchCity = (place) => {
  center.value = {
    lat: place.geometry.location.lat(),
    lng: place.geometry.location.lng(),
  };
  updateCircle();
};

const showResults = () => {
  alert('Show results within ' + radius.value + ' km');
};

const fitMapToBounds = () => {
  if (mapRef.value) {
    const bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(center.value.lat, center.value.lng));
    const northEast = google.maps.geometry.spherical.computeOffset(
      new google.maps.LatLng(center.value.lat, center.value.lng),
      radius.value * 1000, // Convert km to meters
      45
    );
    const southWest = google.maps.geometry.spherical.computeOffset(
      new google.maps.LatLng(center.value.lat, center.value.lng),
      radius.value * 1000,
      225
    );
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
  console.log(center.value)
  updateCircle();
};

const myLocation = onMounted(() => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      userLocation.value = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      center.value = userLocation.value;
    });
  } else {
    console.error('Geolocation is not supported by this browser.');
  }
});

const refreshPage = () => {
  location.reload(); // Reloads the current page
};
</script>

<template>
    <router-link :to="{ name: 'dashboard'}" class="link">
      <div class="back">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#298E46"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
      </div>
    </router-link>

  <div>{{ store.authUser }}</div>

  <div class="map-container">
    <GoogleMap
      api-key="AIzaSyDVsZaS67NSKA-13blSRq7X0vvfhdlKX2Y"
      :center="center"
      :zoom="10"
      style="width: 100%; height: 100%"
      @load="mapRef.value = $event"
      @click="handleMapClick"

    >
      <Circle :options="circleOptions" />
      <Marker :options="{ position: center }" />
    </GoogleMap>
    
    <form @submit.prevent="myLocation" class="location-form">

      <div class="controls">
        
        <h4 v-if="store.authUser.is_seller">Auf Ihren Hof klicken</h4>
        <h4 v-else>Distanz wählen</h4>
        <div class="slider-container">
          <input type="range" min="1" max="20" v-model="radius" @input="updateCircle" />
          <span>{{ radius }} km</span>
        </div>
        <input type="text" v-model="city" placeholder="Search for a city" @keypress.enter="searchCity" />
        <button @click="useCurrentLocation">Use my current location</button>
        <button @click="searchCity">Show results</button>
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