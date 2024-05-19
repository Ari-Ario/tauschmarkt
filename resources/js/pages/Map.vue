<script setup>
import { ref, onMounted, watch } from 'vue';
import { GoogleMap, Marker, Circle } from 'vue3-google-map';

const userLocation = ref(null);

const center = ref({ lat: 34.0522, lng: -118.2437 });
const radius = ref(10); // Default radius in km
const city = ref('');
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
});

const updateCircle = () => {
  circleOptions.value = {
    ...circleOptions.value,
    radius: radius.value * 1000,
  };
};

const useCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      center.value = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      console.log(center.value)
      updateCircle();
    });
  } else {
    alert('Geolocation is not supported by this browser.');
  }
};

const searchCity = async () => {
  const response = await fetch(
    `https://maps.googleapis.com/maps/api/geocode/json?address=${city.value}&key=YOUR_GOOGLE_MAPS_API_KEY`
  );
  const data = await response.json();
  if (data.results.length > 0) {
    center.value = data.results[0].geometry.location;
    updateCircle();
  } else {
    alert('City not found');
  }
};

const showResults = () => {
  alert('Show results within ' + radius.value + ' km');
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
</script>

<template>
  <div class="map-container">
    <GoogleMap
      api-key="AIzaSyDVsZaS67NSKA-13blSRq7X0vvfhdlKX2Y"
      :center="center"
      :zoom="10"
      style="width: 100%; height: 100%"
    >
      <Circle :options="circleOptions"/>
      <Marker :options="{ position: center }" />
    </GoogleMap>
    
    <form @submit.prevent="myLocation" class="location-form">

      <div class="controls">
        <h4>Distanz wählen</h4>
        <div class="slider-container">
          <input type="range" min="1" max="20" v-model="radius" @input="updateCircle" />
          <span>{{ radius }} km</span>
        </div>
        <input type="text" v-model="city" placeholder="Search for a city" @keypress.enter="searchCity" />
        <button @click="useCurrentLocation">Use my current location</button>
        <button @click="showResults">Show results</button>
      </div>
    </form>

  </div>
</template>

<style>
html, body, #app, .map-container {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
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
