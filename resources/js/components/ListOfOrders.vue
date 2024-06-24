<script setup>
import { useAuthStore } from '@/stores/AuthStore';
import { ref , onMounted } from 'vue';
import axios from 'axios';
import FooterUser from '../components/footer/FooterUser.vue';
import FooterSeller from '../components/footer/FooterSeller.vue';
import EnterpriseCard from "../components/EnterpriseCard.vue";
import Header from '../components/header/Header.vue';
import Swal from 'sweetalert2';


const store = useAuthStore();
const userId = store.authUser.id;
// Sample data for orders
const orders = ref([]);

const sellerOrders = async () => {
      try {
        const response = await axios.get(`/api/sellerorder/${userId}`); // Adjust the URL as needed
        orders.value = response.data;
        // console.log(orders);
      } catch (error) {
        console.error('Failed to fetch orders:', error);
      }
    };

const userOrders = async () => {
      try {
        const response = await axios.get(`/api/userorder/${userId}`); // Adjust the URL as needed
        orders.value = response.data;
        // console.log(orders);
      } catch (error) {
        console.error('Failed to fetch orders:', error);
      }
    };

onMounted(() => {
  if (store.authUser.is_seller) {
    sellerOrders();
  } else {
    userOrders();
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
    await axios.put('/api/orders/markAsCollected', {
      orderId: order.id
    });

    // Close the details popup after marking as collected
    closeDetails();
    Swal.fire({
        toast: true,
        icon: "success",
        position: "top-end",
        showConfirmButton: false,
        title: "Abholen erfolgreich!",
        timer: 3000
    })
  } catch (error) {
    console.error('Error marking order as collected:', error);
  }
};

const getEnterprisePicture = (path) => {
    return path ? `storage/${path}` : 'storage/enterprise_images/default.png';
};

const getProfilePicture = (path) => {
    return path ? `storage/${path}` : 'storage/profile_images/default.png';
};
</script>

<template>
      <header>
        <Header />
      </header>
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
        <img :src="getProfilePicture(order.profile_image)" alt="Profil Image" />
        <div>
          <h3>{{ order.firstname }} {{ order.lastname }}</h3>
          <p>{{ order.street }} {{ order.house_number }}, {{ order.zip_code }} {{ order.city }}</p>
        </div>
      </div>
    </div>
    <div class="backdrop" v-if="selectedOrder" @click="closeDetails"></div>
    <div class="order-details-popup" v-if="selectedOrder">
      <div class="details-header">
        <h2>{{ selectedOrder.name }} {{ selectedOrder.name }}</h2>
        <button @click="closeDetails">Close</button>
      </div>
      <img :src="getEnterprisePicture(selectedOrder.enterprise_image)" alt="Image" />
      <p><strong>Strasse:</strong> {{ selectedOrder.address }}</p>
      <p><strong>Abholungszeit:</strong> von {{ selectedOrder.opening }} bis {{ selectedOrder.closing }} </p>
      <p><strong>Bestellungsdatum:</strong> {{ selectedOrder.orderDate }}</p>
      <p><strong>Bestellungsnummer:</strong> {{ selectedOrder.id }}</p>
      <p><strong>Preis:</strong> {{ selectedOrder.price }} CHF</p>

      <div v-if="selectedOrder.picked">
        <p><strong>Abgeholt:</strong> {{ selectedOrder.picked }} </p>
      </div>

      <div class="collect-section" v-if="!selectedOrder.picked">
        <h3>Bestellung zum Abholen</h3>
        <div class="collect-info">
          <div class="quantity">
            <div class="circle">1x</div>
            <p>Surprise Bag</p>
          </div>
          <p>{{ selectedOrder.firstname }}</p>
          <div class="order-code">{{ selectedOrder.orderCode }}</div>
        </div>
        <p>Drücken Sie den Button zum Abholen. Verkäfer und Käufer beachten.</p>
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

  <footer v-if="store.authUser.is_seller">
    <FooterSeller />
  </footer>
  <footer v-else>
    <FooterUser />
  </footer>
</template>

<style scoped>
.orders-page {
  max-width: 600px;
  margin: 64px auto;
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
  text-align: center;
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
