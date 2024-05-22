<script setup>
import { useRoute } from 'vue-router';
import { useAuthStore } from "../../stores/AuthStore"
import LogoutButton from '@/components/LogoutButton.vue';
import Logo from './Logo.vue';

const route = useRoute();
const store = useAuthStore();

</script>
<template>

    <header>
        <div id="header">
            <div class="left">
            <Logo></Logo>
                Tauschmarkt
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
                                    Betrieb Registerieren
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
            <div class="menu-mobile" @click="toggleOverlay">
                <svg xmlns="http://www.w3.org/2000/svg" height="33px" viewBox="0 -960 960 960" width="33px" fill="#298E46">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </div>

        </div>
    </header>

    <div v-if="overlayVisible" class="overlay" @click="toggleOverlay">
        <!-- Optional content inside the overlay -->
        <p>HI there</p>
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
    width: 50vw;
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
    width: 50vw;
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

#getStartedButton {
    border: 2px solid white;
    border-radius: 30px;
    background-color: black;
    padding: 10px;
    font-weight: bold;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media only screen and (max-width: 600px) {

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

    .menu-mobile {
        display: flex;
        margin-right: 10px;
    }

}
</style>