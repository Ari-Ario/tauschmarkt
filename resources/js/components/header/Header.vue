<script setup>
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { useAuthStore } from "../../stores/AuthStore"
import LogoutButton from '@/components/LogoutButton.vue';
import Logo from './Logo.vue';

const route = useRoute();
const store = useAuthStore();
const modalVisible = ref(false);

const toggleModal = () => {
    modalVisible.value = !modalVisible.value;
};

</script>
<template>

    <header>
        <div id="header">
            <div class="left">
            <Logo></Logo>
                Tausch
            </div>
            <div class="right">
                <ul class="menu">

                    <li v-if="!store?.authUser" id="login">

                        <router-link :to="{ name: 'map' }" customv-slot="{ navigate }">
                            <div class="link" @click="navigate" role="link">
                                Karte
                            </div>
                        </router-link>
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

                    <li v-if="store?.authUser" id="dashboard">
                        <router-link :to="{ name: 'dashboard' }" customv-slot="{ navigate }">
                            <div class="link" @click="navigate" role="link">
                                Dashboard
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
                    
                    <li v-if="store?.authUser" id="logout">
                        <div>
                            <LogoutButton>Abmelden</LogoutButton>
                        </div>

                    </li>
                </ul>

            </div>
            <div class="menu-mobile" @click="toggleModal">
                <svg xmlns="http://www.w3.org/2000/svg" height="33px" viewBox="0 -960 960 960" width="33px" fill="#298E46">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
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

            <router-link :to="{ name: 'register' }" customv-slot="{ navigate }">
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
    flex-direction: row;
    align-items: center;
    justify-items: center;
    height: 40px;
    width: 100vw;
    /* position: absolute; */
    background-color: rgba(243, 243, 243, 0.842);

}

.left {
    margin-left: 10px;
    width: 40vw;
    height: auto;
    color: #298E46;
    font-size: large;
    align-items: center;
}
/* .logo-name {
    display:grid;
    align-items: center;
} */

.right {
    width: 60vw;
    height: 63px;
}

.menu {
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

.link {
    text-decoration: none;
    cursor: pointer;
    color: #298E46;
}

#logo {
    height: 40px;
}

.menu-mobile {
    display: none;
}

#getStartedButton, .btn {
    border-radius: 5px;
    background-color:  #298E46;
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


.green {
    background-color: #298E46;
}

.red {
    background-color: #C71610;
}

.blue {
    background-color: #2196F3;
}
@media only screen and (max-width: 680px) {

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

    #getStartedButton, .btn {
        border-radius: 5px;
        margin-top: 10px;
    }

    .menu-mobile {
        display: flex;
        margin-right: 10px;
    }

}
</style>