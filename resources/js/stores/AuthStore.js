
import { defineStore } from 'pinia'
import AuthService from "@/services/AuthService";


export const useAuthStore = defineStore('AuthStore', {
  
  state: () => {
    // id: "user"
    return {
        user: JSON.parse(localStorage.getItem("user")),
    }
    //     return {
    //     user: null,
    // }
  },

  
  actions: {

    async getAuthUser() {
      try {
        let response = await AuthService.getAuthUser();
        // console.log('getAuthUser response:', response);

        if (response.data && response.data.data) {
          this.user = response.data.data;
          // console.log('User data:', this.user);
          localStorage.setItem("user", JSON.stringify(this.user));
          return response.data.data;
        } else {
          console.warn('No user data found in response');
          this.user = null;
          localStorage.removeItem("user");
        }
      } catch (error) {
        console.error('Error in getAuthUser:', error);
        this.user = null;
        localStorage.removeItem("user");
      }
    },

    async logout(){
      return AuthService.logout().then(() => {
        this.user = null;
        localStorage.removeItem("user");
      });
    }

  },


  // get Elements from store
  getters: {

    authUser: (state) => state.user,
  },


})