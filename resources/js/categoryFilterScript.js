import { ref } from 'vue';
import axios from 'axios';

export const categories = ref([]);
export const selectedCategories = ref([]);

export async function updateFilteredProducts() {
  try {
    const response = await axios.get('products', {
      params: {
        categories: selectedCategories.value
      }
    });
    // Handle the response here
  } catch (error) {
    console.error('Error updating filtered products:', error);
  }
}
