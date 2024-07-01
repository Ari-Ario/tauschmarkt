<script setup>
import { ref, onMounted } from 'vue';
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

const statistics = ref({
  totalSales: 500,
  positiveReviews: 95,
  totalProductReview: 20
});

const fetchProductReviews = async () => {
  try {
    const response = await axios.get(`/api/productreview/${sellerId}`);
    productReviews.value = response.data;
    // orderCount.value = response.data.orderCount;
    // console.log(productReviews.value)
  } catch (error) {
    console.error('Failed to fetch product reviews:', error);
  }
};

const fetchOrderCount = async () => {
  try {
    const response = await axios.get(`/api/order/average-rating/${sellerId}`);
    orderCount.value = response.data;
    console.log(orderCount.value)

  } catch (error) {
    console.error('Failed to fetch average rating:', error);
  }
};

onMounted(() => {
  fetchProductReviews();
  fetchOrderCount();
});
</script>


<template>
  <div class="seller-statistics">
    <div class="stat">
      <h4>Gesamte Verkafe</h4>
      <p>{{ orderCount.orderCount }}</p>
    </div>
    <div class="stat">
      <h4>Bewertung der Produkte</h4>
      <p>{{ productReviews.averageRating }}%</p>
    </div>
    <div class="stat">
      <h4>Alle Bewertungen</h4>
      <p>{{  productReviews.allProducts}}</p>
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
  border-radius: 10px;
  width: 30%;
}
</style>
