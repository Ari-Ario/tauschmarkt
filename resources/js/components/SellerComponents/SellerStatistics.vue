<script setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../../stores/AuthStore';

// Fetch user profile data on mount
const store = storeToRefs(useAuthStore());
const sellerId = store?.authUser?.value.id;
const products = ref([]);
const categories = ref([]);

const statistics = ref({
  totalSales: 500,
  positiveReviews: 95,
  totalProducts: 20
});

const fetchProduct = async () => {
  // console.log(sellerId);

    try {
        const response = await axios.get(`/api/product/${sellerId}`);
        // categories.value = response.data.categories;
        products.value = response.data.products.data;
        console.log(products.value)
        // console.log(categories.value)

    } catch (error) {
        console.error('Failed to fetch Product:', error);
    }
};
onMounted(() => {
    fetchProduct();
});
</script>

<template>
  <div class="seller-statistics">
    <div class="stat">
      <h4>Total Sales</h4>
      <p>{{ statistics.totalSales }}</p>
    </div>
    <div class="stat">
      <h4>Positive Reviews</h4>
      <p>{{ statistics.positiveReviews }}%</p>
    </div>
    <div class="stat">
      <h4>Total Products</h4>
      <p>{{ statistics.totalProducts }}</p>
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
