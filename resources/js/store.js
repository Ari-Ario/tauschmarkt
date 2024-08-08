// store.js
import { ref, computed, watch } from 'vue';

const cart = ref([]);

// Load cart from local storage on initialization
const loadCartFromLocalStorage = () => {
    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
        cart.value = JSON.parse(storedCart);
    }
};

// Save cart to local storage whenever it changes
const saveCartToLocalStorage = () => {
    watch(cart, (newCart) => {
        localStorage.setItem('cart', JSON.stringify(newCart));
    }, { deep: true });
};

export function useCart() {
    // Load cart from local storage when the app starts
    loadCartFromLocalStorage();
    // Save cart to local storage whenever it changes
    saveCartToLocalStorage();
    
    const addToCart = (product) => {
        const firstTotalQuantity = product.quantity;
        // console.log(product.quantity)
        const existingProduct = cart.value.find(item => item.id === product.id);
        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.value.push({ ...product, quantity: 1, totalQuantity: firstTotalQuantity });
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

    const getExistingProduct = (productId) => {
        return cart.value.find(item => item.id === productId);
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
        getExistingProduct
    };
}
