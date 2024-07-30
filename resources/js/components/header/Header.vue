<script setup>
import { useRoute } from 'vue-router';
import { ref, computed } from 'vue';
import { useAuthStore } from "../../stores/AuthStore"
import LogoutButton from '@/components/LogoutButton.vue';
import Logo from './Logo.vue';
import { useCart } from '../../store';
import CartList from "../../pages/CartList.vue"

const { cartCount } = useCart();

const route = useRoute();
const store = useAuthStore();
const modalVisible = ref(false);
const cart = computed(() => usePage().props.cart);
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const toggleModal = () => {
    modalVisible.value = !modalVisible.value;
};

</script>
<template>

    <header>
        <div id="header">
            <div class="left">
            <Logo></Logo>
            </div>

            <div v-if="!store?.authUser?.is_seller" class="center">
                <router-link :to="{ name: 'cartList' }" customv-slot="{ navigate }">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#004d40"><path d="M226.67-80q-27 0-46.84-19.83Q160-119.67 160-146.67v-506.66q0-27 19.83-46.84Q199.67-720 226.67-720h100v-6.67q0-64 44.66-108.66Q416-880 480-880t108.67 44.67q44.66 44.66 44.66 108.66v6.67h100q27 0 46.84 19.83Q800-680.33 800-653.33v506.66q0 27-19.83 46.84Q760.33-80 733.33-80H226.67Zm0-66.67h506.66v-506.66h-100v86.66q0 14.17-9.61 23.75-9.62 9.59-23.84 9.59-14.21 0-23.71-9.59-9.5-9.58-9.5-23.75v-86.66H393.33v86.66q0 14.17-9.61 23.75-9.62 9.59-23.84 9.59-14.21 0-23.71-9.59-9.5-9.58-9.5-23.75v-86.66h-100v506.66ZM393.33-720h173.34v-6.67q0-36.33-25.17-61.5-25.17-25.16-61.5-25.16t-61.5 25.16q-25.17 25.17-25.17 61.5v6.67ZM226.67-146.67v-506.66 506.66Z"/></svg>
                    
                    <div class="cart-number">
                        {{ cartCount }}
                    </div>
                </router-link>
            </div>
                
            <!-- <div v-if="!store?.authUser" class="" style="text-align: end;"> -->
                <div v-if="!store?.authUser" class="right">
                    <ul class="menu">

                        <li v-if="!store?.authUser" id="login">
                            <div :class="{ active: currentPage !== 'Map' }, menu-container">
                                <router-link :to="{ name: 'mapUser'}" class="link">
                                    <div class="switch-container">
                                    Karte📍
                                    </div>
                                </router-link>
                            </div>
                        </li>

                        <li v-if="!store?.authUser">
                            <router-link :to="{ name: 'home' }" customv-slot="{ navigate }">
                                <div class="link" @click="navigate" role="link">
                                    <p class="logo-name">
                                        Startseite
                                    </p>
                                </div>
                            </router-link>
                        </li>

                        <li v-if="!store?.authUser" id="login">

                            <router-link :to="{ name: 'login' }" customv-slot="{ navigate }">
                                <div class="link" @click="navigate" role="link">
                                    Anmelden
                                </div>
                            </router-link>
                        </li>

                        <li v-if="!store?.authUser" id="getStarted">
                            <router-link :to="{ name: 'register' }" customv-slot="{ navigate }">
                                <div class="link" @click="navigate" role="link">
                                    <div class="link" id="getStartedButton">
                                        Register
                                    </div>
                                </div>
                            </router-link>
                        </li>

                        <li v-if="!store?.authUser" id="getStarted">
                            <router-link :to="{ name: 'registerSeller' }" customv-slot="{ navigate }">
                                <div class="link" @click="navigate" role="link">
                                    <div class="link" id="getStartedButton">
                                        Betrieb Register
                                    </div>
                                </div>
                            </router-link>
                        </li>


                        <li v-if="store?.authUser" id="dashboard">
                            <router-link :to="{ name: 'dashboard' }" customv-slot="{ navigate }">
                                <div class="link" @click="navigate" role="link">
                                    Dashboard
                                </div>
                            </router-link>
                        </li>

                    </ul>

                </div>

                <div v-if="!store.authUser" class="menu-mobile " >
                    <button v-if="!store?.authUser" style="margin-right: 10px;">
                            <div :class="{ active: currentPage !== 'Map' }, menu-container">
                                <router-link :to="{ name: 'mapUser'}" class="link">
                                    <div class="switch-container">
                                    📍
                                    </div>
                                </router-link>
                            </div>
                    </button>

                    <svg @click="toggleModal" xmlns="http://www.w3.org/2000/svg" height="33px" viewBox="0 -960 960 960" width="33px" fill="#004d40">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg>
                </div>
            <!-- </div> -->


            <div v-else class="">
                <ul class="menu-login">
                    <li v-if="store?.authUser" id="logout">
                        <div>
                            <LogoutButton>Abmelden</LogoutButton>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </header>

    <div v-if="modalVisible" class="modal-overlay" @click="toggleModal">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <Logo></Logo>
                <button @click="toggleModal" class="close-btn">&times;</button>
            </div>
            <!-- <img src="" alt="Food" class="modal-image"> -->

            <router-link :to="{ name: 'login' }" customv-slot="{ navigate }">
                <div class="link btn green" @click="navigate" role="link">
                    <div class="link" style="color: white;" >
                        Anmelden
                    </div>
                </div>
            </router-link>

            <router-link :to="{ name: 'login' }" customv-slot="{ navigate }" >
                <div class="link btn red" @click="navigate" role="link">
                    <div class="link" style="color: white;">
                        Mit Google Anmelden
                    </div>
                </div>
            </router-link>
            <p>Oder</p>

            <router-link :to="{ name: 'register' }" customv-slot="{ navigate }">
                <div class="link" @click="navigate" role="link">
                    <div class="link" id="getStartedButton">
                        Register
                    </div>
                </div>
                </router-link>

            <router-link :to="{ name: 'registerSeller' }" customv-slot="{ navigate }">
                <div class="link" @click="navigate" role="link">
                    <div class="link" id="getStartedButton">
                        Betrieb Register
                    </div>
                </div>
            </router-link>

         </div>
    </div>

</template>

<style scoped>

#header {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    justify-content: space-around;
    align-items: center;
    /* justify-content: space-around; */
    height: 40px;
    width: 100%;
    /* position: absolute; */
    background-color: rgba(243, 243, 243, 0.842);
    z-index: 5;

}

.left {
    margin-left: 10px;
    width: 40%;
    height: auto;
    color:  #004d40;
    font-size: large;
    align-items: center;
}
/* .logo-name {
    display:grid;
    align-items: center;
} */
 .center {
    position: relative;
    display: inline-block;
 }

.right {
    width: 50%;
    height: 63px;
    align-items: end;
}

.menu , .menu-login {
    display: flex;
    flex-direction: row;
    justify-content: end;
    list-style: none;
    gap: 20px;
    margin-right: 40px;
    align-items: center;
    height: 100%;
    margin-bottom: 0px;
    margin-top: 0px;
    font-weight: bold;

}

.link, router-link {
    text-decoration: none;
    cursor: pointer;
    color:  #004d40;
}

#logo {
    height: 40px;
}

.menu-mobile {
    display: none;
}

#getStartedButton, .btn {
    border-radius: 5px;
    background-color:   #004d40;
    color: white;
    padding: 10px;
    font-weight: bold;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: rgba(152, 152, 152, 0.695);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 90%;
    max-height: 90%;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.switch-container {
    display: flex;
}


.green {
    background-color: #298E46;
}

.red {
    background-color: #C71610;
}

.blue {
    background-color: #2196F3;
}

.cart-number {
    position: absolute;
    bottom: 8px;
    right: -1px;
    background-color: #004D40;
    color: white;
    border-radius: 50%;
    padding: 4px;
    font-size: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translate(50%, 50%);
}
@media only screen and (max-width: 1024px) {
    #header {
        max-width: 100vw;
    }
    .menu {
        display: none;
        gap: 5px;
        margin-right: 5px;
        align-items: center;
        height: 100%;
        margin-bottom: 0px;
        margin-top: 0px;
        font-weight: bold;
        font-size: smaller;

    }
    .right {
    display: flex;
    padding-right: 0;
    margin-right: 0;
    width: 40%;

    }
    .left {
        padding-right: 0;
        width: 60%;
    }
    .center {

        justify-content: center;
    }
    #getStartedButton, .btn {
        border-radius: 5px;
        margin-top: 10px;
    }

    .menu-mobile {
        display: flex;
        margin-right: 25px;
    }

}
</style>