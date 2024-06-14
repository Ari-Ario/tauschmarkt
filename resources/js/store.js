// store.js
import { ref, computed } from 'vue';

const cart = ref([]);

export function useCart() {
    const addToCart = (product) => {
        const existingProduct = cart.value.find(item => item.id === product.id);
        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.value.push({ ...product, quantity: 1 });
        }
    };

    const removeItemFromCart = (product) => {
        const existingProduct = cart.value.find(item => item.id === product.id);
        if (existingProduct && (existingProduct.quantity > 1)) {
            existingProduct.quantity -= 1;
        } else {
            return
        }
    };

    const removeFromCart = (productId) => {
        const productIndex = cart.value.findIndex(item => item.id === productId);
        if (productIndex !== -1) {
            cart.value.splice(productIndex, 1);
        }
    };



    const cartCount = computed(() => {
        return cart.value.reduce((total, product) => total + product.quantity, 0);
    });

    const cartItems = computed(() => cart.value);

    return {
        cartItems,
        cartCount,
        addToCart,
        removeItemFromCart,
        removeFromCart,
    };
}
