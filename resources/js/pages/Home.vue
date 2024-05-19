<template>
    <div>
        <h1>Startseite</h1>
        <router-link to="/login">Link zur Login Seite</router-link>
        <br>
        <router-link to="/register">Link zur RegisterUser Seite</router-link>
        <br>
        <router-link to="/registerSeller">Link zur RegisterSeller Seite</router-link>
        <br>
        <g-map
            :disableUI="false"
            :zoom="12"
            mapType="roadmap"
            :center="{ lat: 38.8977859, lng: -77.0057621 }">
        </g-map>

        <router-link to="/map">Map</router-link>

    </div>
</template>