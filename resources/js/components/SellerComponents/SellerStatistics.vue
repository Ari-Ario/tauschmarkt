<script setup>
import { ref } from 'vue';

const statistics = ref({
  totalSales: 500,
  positiveReviews: 95,
  totalProducts: 20
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
