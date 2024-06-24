<script setup>
import FooterSeller from '../components/footer/FooterSeller.vue';
import { useAuthStore } from '@/stores/AuthStore';
import { ref , onMounted } from 'vue';
import axios from 'axios';

const store = useAuthStore();
const userId = store.authUser.id;
// Sample data for orders
const orders = ref([
  {
    id: null,
    firstname: 'Harfe ',
    lastname: 'Roteerlin',
    address: 'Oranienstraße 13, 10999 Berlin',
    pickupTime: '31-08-2016 16:00 - 16:30',
    orderDate: '31-08-2016 14:34',
    image: '/path/to/image1.jpg',
    price: '3.00',
    picked: false,
    orderCode: 'EYM8FHTW8MXB0'
  },
  {
    id: '2',
    firstname: ' Berlin',
    lastname: 'Rot Berlin',   
    address: 'Manteuffelstr 1, 12103 Berlin',
    pickupTime: '31-08-2016 16:00 - 16:30',
    orderDate: '31-08-2016 14:34',
    image: '/path/to/image2.jpg',
    price: '3.00',
    picked: false,
    orderCode: 'ABC123XYZ456'
  },
  // Add more orders as needed
]);

onMounted(async () => {
      try {
        const response = await axios.get(`/api/payment/orders/${userId}`); // Adjust the URL as needed
        orders.value = response.data;
        console.log(orders);
      } catch (error) {
        console.error('Failed to fetch orders:', error);
      }
    });

const selectedOrder = ref(null);

const selectOrder = (order) => {
  selectedOrder.value = order;
};

const closeDetails = () => {
  selectedOrder.value = null;
};

const markAsCollected = async (order) => {
  try {
    // Update the status in the frontend
    order.picked = true;

    // Send a request to update the status in the database
    await axios.post('/api/orders/markAsCollected', {
      orderId: order.id
    });

    // Close the details popup after marking as collected
    closeDetails();
  } catch (error) {
    console.error('Error marking order as collected:', error);
  }
};

</script>

<template>
  <div class="orders-page">
    <div class="order-details">
        <h3>Bestellungen</h3>
      </div>
    <div class="orders-list">
      <div 
        v-for="order in orders" 
        :key="order.id" 
        class="order-item" 
        @click="selectOrder(order)"
      >
        <img :src="order.image" alt="Order Image" />
        <div>
          <h3>{{ order.name }}</h3>
          <p>{{ order.address }}</p>
        </div>
      </div>
    </div>
    <div class="backdrop" v-if="selectedOrder" @click="closeDetails"></div>
    <div class="order-details-popup" v-if="selectedOrder">
      <div class="details-header">
        <h2>{{ selectedOrder.name }}</h2>
        <button @click="closeDetails">Close</button>
      </div>
      <img :src="selectedOrder.image" alt="Order Image" />
      <p><strong>Address:</strong> {{ selectedOrder.address }}</p>
      <p><strong>Pickup Time:</strong> {{ selectedOrder.pickupTime }}</p>
      <p><strong>Order Date:</strong> {{ selectedOrder.orderDate }}</p>
      <p><strong>Order ID:</strong> {{ selectedOrder.id }}</p>
      <p><strong>Price:</strong> {{ selectedOrder.price }} EUR</p>
      <div>
        <label for="picked">Picked Up:</label>
        <input type="checkbox" id="picked" v-model="selectedOrder.picked" />
      </div>
      <div class="collect-section">
        <h3>Order to be collected</h3>
        <div class="collect-info">
          <div class="quantity">
            <div class="circle">1x</div>
            <p>Surprise Bag</p>
          </div>
          <p>{{ selectedOrder.name }}</p>
          <div class="order-code">{{ selectedOrder.orderCode }}</div>
        </div>
        <p>Swipe below and show the order to the staff. Make sure to swipe only when you're in the store ready to collect your meal.</p>
        <button class="swipe-to-collect" @click="markAsCollected(selectedOrder)">
          <span class="swipe-icon">➔</span>
          Abholen
        </button>
      </div>
    </div>
    <div v-show="currentPage !== 'Map'">
        <FooterSeller />
    </div>
  </div>
</template>

<style scoped>
.orders-page {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 300px;
  /* padding: 20px; */
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.order-details {
  text-align: center;
}

.orders-list {
  flex: 1;
  width: 100%;
  /* min-width: 300px;
  max-width: 400px; */
  border-right: 1px solid #ddd;
}

.order-item {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  cursor: pointer;

  background-color: none;
  border: none;
  border-radius: 10px;
  text-align: center;
  cursor: pointer;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.order-item img {
  width: 50px;
  height: 50px;
  margin-right: 10px;
  border-radius: 50%;
}

.order-item h3 {
  margin: 0;
  font-size: 1rem;
}

.order-item p {
  margin: 0;
  font-size: 0.875rem;
  color: #666;
}

.backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 10;
}

.order-details-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 20px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 20;
  width: 300px;
  /* max-width: 300px; */
  border-radius: 8px;
}

.details-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 10px;
}

.details-header h2 {
  margin: 0;
  font-size: 1.5rem;
}

.details-header button {
  padding: 5px 10px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.order-details-popup img {
  width: 100px;
  height: 100px;
  margin: 10px 0;
  border-radius: 50%;
}

.order-details-popup p {
  margin: 10px 0;
  font-size: 1rem;
}

.order-details-popup label {
  font-size: 1rem;
  margin-right: 10px;
}

.order-details-popup input[type="checkbox"] {
  transform: scale(1.5);
}

.collect-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #ddd;
  text-align: center;
}

.collect-section h3 {
  margin: 0 0 10px;
  font-size: 1.2rem;
}

.collect-info {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
}

.collect-info .quantity {
  display: flex;
  align-items: center;
  margin-right: 10px;
}

.collect-info .circle {
  display: inline-block;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #42b983;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.collect-info p {
  margin: 0;
}

.order-code {
  background-color: #42b983;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  margin-bottom: 10px;
}

.swipe-to-collect {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #42b983;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-size: 1rem;
}

.swipe-icon {
  margin-right: 10px;
}

@media (max-width: 600px) {

.orders-page {
  width: auto;
  height: auto;

  padding: auto;
}
.orders-list {
  top: 0;
  width: 100%;
}
.order-details-popup {
  width: 90%;
}

}
</style>
