import{_ as V,r as i,i as A,j as F,a as D,o as f,c as P,b as m,e as S,d as a,k as r,w as H,f as G,v as K,t as W,F as w,l as $,m as z,n as N,p as Y,g as j,h as Z}from"./app-DWDepuyr.js";import{S as L,P as q,E as J}from"./index-BF5qJmFv.js";import{l as c,s as x,a as Q}from"./locals-C3iIwN0i.js";var X={VITE_BASE_URL:"https://kleinhaendler.ch/api",VITE_PUSHER_APP_KEY:"",VITE_PUSHER_HOST:"",VITE_PUSHER_PORT:"",VITE_PUSHER_SCHEME:"",VITE_PUSHER_APP_CLUSTER:"",BASE_URL:"/build/",MODE:"production",DEV:!1,PROD:!0,SSR:!1};const b=p=>(Y("data-v-b6b83053"),p=p(),j(),p),ee=b(()=>a("div",{class:"back"},[a("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24px",viewBox:"0 -960 960 960",width:"24px",fill:"#298E46"},[a("path",{d:"M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"})])],-1)),te={class:"map-container"},oe={class:"controls"},ae=b(()=>a("h4",null,"Distanz wählen",-1)),le={class:"slider-container"},ne={__name:"MapUser",setup(p){const y=Z(),h=i(null),n=i({lat:46.938749674988486,lng:7.459564360522899}),s=i(10);i("");const v=i(null),E=i([]),I=X.VUE_APP_GOOGLE_MAP_API_KEY,d=i({center:n.value,radius:s.value*1e3,fillColor:"#0088ff",fillOpacity:.2,strokeColor:"#0088ff",strokeOpacity:.5,strokeWeight:2});A([n,s],()=>{d.value={...d.value,center:n.value,radius:s.value*1e3},k()});const _=()=>{d.value={...d.value,radius:s.value*1e3},k()},O=e=>{if(e.geometry){n.value={lat:e.geometry.location.lat(),lng:e.geometry.location.lng()},c.value={latitude:e.geometry.location.lat(),longitude:e.geometry.location.lng()},_();const t=e.address_components;let l="";for(const o of t)if(o.types.includes("locality")){l=o.long_name;break}console.log(`City: ${l}`)}else console.error("Place has no geometry")},k=()=>{if(v.value){const e=new google.maps.LatLngBounds,t=new google.maps.LatLng(n.value.lat,n.value.lng),l=s.value*1e3,o=google.maps.geometry.spherical.computeOffset(t,l,45),u=google.maps.geometry.spherical.computeOffset(t,l,225);e.extend(t),e.extend(o),e.extend(u),v.value.fitBounds(e)}},R=e=>{n.value={lat:e.latLng.lat(),lng:e.latLng.lng()},c.value={latitude:e.latLng.lat(),longitude:e.latLng.lng()},_()},M=async(e,t,l,o)=>{try{const u=await $.get(`/api/enterprises/${o}`,{params:{latitude:e,longitude:t,distance:l}});E.value=u.data}catch(u){console.error("Error loading enterprises:",u)}},C=F(async()=>{navigator.geolocation?navigator.geolocation.getCurrentPosition(async e=>{h.value={lat:e.coords.latitude,lng:e.coords.longitude},n.value=h.value,c.value={latitude:e.coords.latitude,longitude:e.coords.longitude},await M(c.value.latitude,c.value.longitude,s.value,1)}):console.error("Geolocation is not supported by this browser.")}),g=i(null),T=()=>{if(g.value)return;const e=document.getElementById("autocomplete");g.value=new google.maps.places.Autocomplete(e,{types:["(cities)"]}),g.value.addListener("place_changed",()=>{const t=g.value.getPlace();if(t.geometry){const l=t.geometry.location.lat(),o=t.geometry.location.lng();x(l,o),console.log(`City: ${t.formatted_address}`)}else console.error("Place has no geometry")})},U=e=>{y.push({name:"index",params:{id:e}})},B=()=>{x(c.value.latitude,c.value.longitude),Q(s.value),y.push({name:"home"})};return(e,t)=>{const l=D("router-link");return f(),P(w,null,[m(l,{to:{name:"home"},class:"link"},{default:S(()=>[ee]),_:1}),a("div",te,[m(r(J),{"api-key":r(I),center:n.value,zoom:10,style:{width:"100%",height:"100%"},onLoad:t[0]||(t[0]=o=>v.value.value=o),onClick:R},{default:S(()=>[(f(!0),P(w,null,z(E.value,o=>(f(),N(r(L),{key:o.id,options:{center:{lat:parseFloat(o.latitude),lng:parseFloat(o.longitude)},radius:500,fillColor:"#FF0000",fillOpacity:.5,strokeColor:"#FF0000",strokeOpacity:.8,strokeWeight:2},onClick:()=>U(o.id)},null,8,["options","onClick"]))),128)),m(r(L),{options:d.value},null,8,["options"]),m(r(q),{options:{position:n.value}},null,8,["options"])]),_:1},8,["api-key","center"]),a("form",{onSubmit:t[2]||(t[2]=H((...o)=>r(C)&&r(C)(...o),["prevent"])),class:"location-form"},[a("div",oe,[a("input",{id:"autocomplete",type:"text",placeholder:"Enter a city or location",onFocus:T},null,32),a("button",{onClick:O},"Stadt suchen"),ae,a("div",le,[G(a("input",{type:"range",min:"1",max:"20","onUpdate:modelValue":t[1]||(t[1]=o=>s.value=o),onInput:_},null,544),[[K,s.value]]),a("span",null,W(s.value)+" km",1)]),a("button",{onClick:B},"Suchen")])],32)])],64)}}},ce=V(ne,[["__scopeId","data-v-b6b83053"]]);export{ce as default};
