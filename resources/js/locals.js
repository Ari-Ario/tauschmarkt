// src/globals/locals.js
import { ref } from 'vue';

export const location = ref({ latitude: 47.37633235605227, longitude: 8.540486878401508 });
export const distance = ref(20);

export const setLocation = (lat, lng) => {
  location.value.latitude = lat;
  location.value.longitude = lng;
};

export const setDistance = (dist) => {
  distance.value = dist;
};
