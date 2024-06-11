// store.js
import { ref, computed } from 'vue';

const cart = ref([]);

export function useCart() {
    const addToCart = (product) => {
        cart.value.push(product);
    };

    const removeFromCart = (productId) => {
        cart.value = cart.value.filter(product => product.id !== productId);
    };

    const cartItems = computed(() => cart.value);

    return {
        cart,
        cartItems,
        addToCart,
        removeFromCart,
    };
}
