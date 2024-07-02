<script setup>
import { ref, onMounted, onBeforeMount } from 'vue';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../../stores/AuthStore';
import axios from 'axios';

// Fetch user profile data on mount
const store = useAuthStore();
const { authUser } = storeToRefs(store);
const sellerId = authUser?.value.id;
const productReviews = ref([]);
const averageRating = ref(null);
const orderCount = ref(0);

const fetchProductReviews = async () => {
  try {
    const response = await axios.get(`/api/productreview/${sellerId}`);
    productReviews.value = response.data;
    averageRating.value = productReviews.value.averageRating.toString().slice(0, 4)
    // console.log(averageRating.value)
  } catch (error) {
    console.error('Failed to fetch product reviews:', error);
  }
};

const fetchOrderCount = async () => {
  try {
    const response = await axios.get(`/api/order/average-rating/${sellerId}`);
    orderCount.value = response.data;
    // console.log(orderCount.value)

  } catch (error) {
    console.error('Failed to fetch average rating:', error);
  }
};

onBeforeMount(() => {
  fetchProductReviews();
  fetchOrderCount();
});

const twoDecimals= (value) => {
  return value.toString().slice(0, 4)
}

</script>


<template>
  <div class="seller-statistics">
    <div class="stat">
      <h4>Gesamte Verkäufe</h4>
      <p>{{ orderCount.orderCount }}</p>
    </div>
    <div class="stat">
      <h4>Durchschnitliche Bewertung </h4>
      <p> {{ averageRating }} </p>
      <div class="stars">
        <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= productReviews.averageRating }">&#9733;</span>
      </div>
    </div>
    <div class="stat">
      <h4> Gesamte Bewertungen </h4>
      <p>{{  productReviews.allProducts }}</p>
    </div>
  </div>
</template>

<style scoped>
.seller-statistics {
  display: flex;
  justify-content: space-around;
  margin: 20px 0 60px 0;
}
.stat {
  text-align: center;
  background-color: #f0f0f0;
  padding: 10px;
  border: 1px solid rgba(0, 0, 0, 0.272);
  border-radius: 10px;
  width: 30%;
}

.stars {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  /* border: 1px solid black; */
}

.star {
  font-size: 24px;
  color: #ccc;
  cursor: pointer;
}
.star-rating {
  font-size: 48px;

}

.star.filled {
  color: #ffb400;
}
</style>
