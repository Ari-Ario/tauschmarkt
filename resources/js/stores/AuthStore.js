
import { defineStore } from 'pinia'
import AuthService from "@/services/AuthService";


export const useAuthStore = defineStore('AuthStore', {
  
  state: () => {
    id: "user"
    return {
        user: JSON.parse(localStorage.getItem("user")),
    }
  },

  
  actions: {

    async getAuthUser(){
        try{
          let response = await AuthService.getAuthUser();
          this.user = response.data.data;
          localStorage.setItem("user", JSON.stringify(this.user));

          return response.data.data;
        }
        catch(error){
          this.user = null;
        }
    },

    async logout(){
      return AuthService.logout().then(() => {
        this.user = null;
      });
    }

  },


  // get Elements from store
  getters: {

    authUser: (state) => state.user,
  },


})