<script setup>
import { computed, reactive, defineProps } from 'vue';
// import UserLayouts from './Layouts/UserLayouts.vue';
import { useRouter } from 'vue-router';
import { useCart } from '../store'; // Adjust the path as needed

const router = useRouter();
const { cartItems, cartCount, addToCart, removeFromCart, updateQuantity } = useCart();

const props = defineProps({
    userAddress: Object
});

const form = reactive({
    address1: null,
    state: null,
    city: null,
    zipcode: null,
    country_code: null,
    type: null,
});

const formFilled = computed(() => {
    return form.address1 !== null &&
        form.state !== null &&
        form.city !== null &&
        form.zipcode !== null &&
        form.country_code !== null &&
        form.type !== null;
});

const carts = cartItems;
const total = computed(() => carts.value.reduce((sum, item) => sum + (item.price * item.quantity), 0));

const itemId = (id) => carts.value.findIndex((item) => item.product_id === id);

const update = (product, quantity) => {
    updateQuantity(product, quantity);
};

const remove = (product) => {
    removeFromCart(product.id);
};

function submit() {
    router.push({
        name: 'checkout.store',
        params: {
            carts: carts.value,
            total: total.value,
            address: form
        },
        query: {}
    });
}
</script>

<template>
        <section class="body">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div class="lg:w-2/3 md:w-1/2 rounded-lg">

                    <!-- list of cart -->

                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Product</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Price</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in carts" :key="product.id">
                                <td class="w-32 p-4">
                                    <img v-if="product.product_images && product.product_images.length > 0"
                                        :src="`/${product.product_images[0].image}`" alt="Product Image" class="img-thumbnail">
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                        alt="No Image Available" class="img-thumbnail">
                                </td>
                                <td class="font-weight-bold text-dark">
                                    {{ product.title }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button @click.prevent="update(product, product.quantity - 1)"
                                            :disabled="product.quantity <= 1"
                                            :class="['btn', 'btn-outline-secondary', product.quantity > 1 ? 'text-primary' : 'disabled']"
                                            type="button">
                                            <i class="bi bi-dash">-</i>
                                        </button>
                                        <input type="number" v-model="product.quantity" class="form-control qty w-25 mx-2"
                                            placeholder="1" style="max-width: 40px;" required>
                                        <button @click.prevent="update(product, product.quantity + 1)"
                                            class="btn btn-outline-secondary text-primary" type="button">
                                            <i class="bi bi-plus">+</i>
                                        </button>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-dark">
                                    ${{ product.price }}
                                </td>
                                <td>
                                    <!-- <button @click="remove(product)"
                                        class="btn btn-danger">Remove</button> -->
                                        <div @click="remove(product)">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EA3323"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                                        </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- end -->
                </div>
                <div class="details md:w-1/2 bg-white flex  mt-8 ">
                    <h2 class="text-dark mb-4">Summary</h2>
                    <p class="mb-5 text-muted">Total: ${{ total }}</p>

                    <div v-if="userAddress">
                        <h2 class="text-dark mb-4">Shipping Address</h2>
                        <p class="text-muted mb-5">{{ userAddress.address1 }} , {{ userAddress.city }}, {{ userAddress.zipcode }}</p>
                        <p class="text-muted mb-5">or you can add new below</p>
                    </div>
                    <button v-else type="submit" class="btn btn-secondary">Zur Kasse</button>
<!--                     <div v-else>
                        <p class="text-muted mb-5">Add shipping address to continue</p>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="address1">Address 1</label>
                            <input type="text" id="address1" name="address1" v-model="form.address1"
                                class="form-control" placeholder="Enter address">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" v-model="form.city"
                                class="form-control" placeholder="Enter city">
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" v-model="form.state"
                                class="form-control" placeholder="Enter state">
                        </div>
                        <div class="form-group">
                            <label for="zipcode">Zipcode</label>
                            <input type="text" id="zipcode" name="zipcode" v-model="form.zipcode"
                                class="form-control" placeholder="Enter zipcode">
                        </div>
                        <div class="form-group">
                            <label for="country_code">Country Code</label>
                            <input type="text" id="country_code" name="country_code" v-model="form.country_code"
                                class="form-control" placeholder="Enter country code">
                        </div>
                        <div class="form-group">
                            <label for="type">Address Type</label>
                            <input type="text" id="type" name="type" v-model="form.type"
                                class="form-control" placeholder="Enter address type">
                        </div>

                        <button v-if="formFilled || userAddress" type="submit"
                            class="btn btn-primary">Checkout</button>
                        <button v-else type="submit"
                            class="btn btn-secondary">Add Address to continue</button>
                    </form>

                    <p class="text-muted mt-3">Continue Shopping</p> -->
                </div>
            </div>
        </section>
</template>

<style scoped>
/* Container styles for responsive design */
.body {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    margin: 0;
}
/* Container styles for responsive design */
.container {
    max-width: 100%;
    padding: 0;
}
.details {
    text-align: center;
    padding: 0;
    margin: 0;
}

@media (min-width: 576px) {
    .container {
        max-width: 540px;
    }
}

@media (min-width: 768px) {
    .container {
        max-width: 720px;
    }
}

@media (min-width: 992px) {
    .container {
        max-width: 960px;
    }
}

@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}

/* Table styling */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table .thead-dark th {
    color: #fff;
    background-color: #004D40;
    border-color: #454d55;
}

/* Form styling */
.form-group {
    margin-bottom: 1rem;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Button styling */
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #004085;
}

.btn-secondary {
    color: #fff;
    background-color: #004D40;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-outline-secondary {
    color: #6c757d;
    background-color: transparent;
    background-image: none;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary.disabled,
.btn-outline-secondary:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

/* Miscellaneous styling */
.text-gray-600 {
    color: #6c757d !important;
}

.text-gray-900 {
    color: #212529 !important;
}

.text-muted {
    color: #6c757d !important;
}

.text-dark {
    color: #343a40 !important;
}

.text-primary {
    color: #007bff !important;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mb-5 {
    margin-bottom: 3rem !important;
}

.mt-0 {
    margin-top: 0 !important;
}

.mt-8 {
    margin-top: 2rem !important;
}

.mx-2 {
    margin-left: 0.5rem !important;
    margin-right: 0.5rem !important;
}

.p-4 {
    padding: 1.5rem !important;
}



.px-5 {
    padding-left: 1.5rem !important;
    padding-right: 1.5rem !important;
}

.py-24 {
    padding-top: 6rem !important;
    padding-bottom: 6rem !important;
}

.img-thumbnail {
    padding: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    max-width: 100%;
    height: auto;
}

.d-flex {
    display: flex !important;
}

.align-items-center {
    align-items: center !important;
}

/* Responsive styles */
@media (max-width: 576px) {
    .container {
        padding: 0;
        margin: 0;
    }

    .table th,
    .table td {
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    .mb-5 {
        margin-bottom: 2rem !important;
    }

    .mt-8 {
        margin-top: 1.5rem !important;
    }

    .px-5 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .py-24 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
    .qty {
        min-width: 10px;
    }
    /* .btn-outline-secondary {
        padding: 0;
        border-radius: 50%;
        max-width: 5px;
    } */
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .table th,
    .table td {
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    .mb-5 {
        margin-bottom: 2rem !important;
    }

    .mt-8 {
        margin-top: 1.5rem !important;
    }

    .px-5 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .py-24 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
}

@media (max-width: 992px) {
    .container {
        padding: 0;
    }

    .table th,
    .table td {
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    .mb-5 {
        margin-bottom: 2rem !important;
    }

    .mt-8 {
        margin-top: 1.5rem !important;
    }

    .px-5 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .py-24 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
}

@media (max-width: 1200px) {
    .container {
        padding: 10px;
    }

    .table th,
    .table td {
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    .mb-5 {
        margin-bottom: 2rem !important;
    }

    .mt-8 {
        margin-top: 1.5rem !important;
    }

    .px-5 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .py-24 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
}
</style>