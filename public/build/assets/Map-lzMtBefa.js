import{_ as N,u as W,r as c,i as $,j as q,a as z,o as u,c as m,b as f,e as x,d as a,k as i,w as Y,q as I,f as j,v as Z,t as J,F as U,l as O,m as Q,n as M,p as X,g as ee,h as te}from"./app-B5EnG7Dm.js";import{S as R,P as oe,E as ae}from"./index-ilmCEBc7.js";import{l as r,s as A,a as ne}from"./locals-Btv2B3v7.js";/* empty css                                                            */var le={VITE_BASE_URL:"https://kleinhaendler.ch/api",VITE_PUSHER_APP_KEY:"",VITE_PUSHER_HOST:"",VITE_PUSHER_PORT:"",VITE_PUSHER_SCHEME:"",VITE_PUSHER_APP_CLUSTER:"",BASE_URL:"/build/",MODE:"production",DEV:!1,PROD:!0,SSR:!1};const k=v=>(X("data-v-95f2a300"),v=v(),ee(),v),se=k(()=>a("div",{class:"back"},[a("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24px",viewBox:"0 -960 960 960",width:"24px",fill:"#298E46"},[a("path",{d:"M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"})])],-1)),re={class:"map-container"},ie={key:0,class:"controls"},ce=k(()=>a("h4",null,"Auf Ihren Hof klicken",-1)),ue={key:1,class:"controls"},de=k(()=>a("h4",null,"Distanz wählen",-1)),pe={class:"slider-container"},ge={__name:"Map",setup(v){const p=W(),E=te(),C=c(null),n=c({lat:46.938749674988486,lng:7.459564360522899}),l=c(10);c("");const y=c(null),w=c([]),B=le.VUE_APP_GOOGLE_MAP_API_KEY,g=c({center:n.value,radius:l.value*1e3,fillColor:"#0088ff",fillOpacity:.2,strokeColor:"#0088ff",strokeOpacity:.5,strokeWeight:2});$([n,l],()=>{g.value={...g.value,center:n.value,radius:l.value*1e3},P()});const _=()=>{g.value={...g.value,radius:l.value*1e3},P()},S=async()=>{document.querySelector('meta[name="csrf-token"]').getAttribute("content");try{const e=await O.put("/api/user/update-location",r.value);console.log("Location updated:",e.data)}catch(e){console.error("Error updating profile:",e)}},T=async()=>{navigator.geolocation?navigator.geolocation.getCurrentPosition(async e=>{r.value={latitude:e.coords.latitude,longitude:e.coords.longitude},n.value={lat:e.coords.latitude,lng:e.coords.longitude},_(),await S(),await L(r.value.latitude,r.value.longitude,l.value,p.authUser.id)}):alert("Geolocation is not supported by this browser.")},V=()=>{S()},F=e=>{if(e.geometry){n.value={lat:e.geometry.location.lat(),lng:e.geometry.location.lng()},r.value={latitude:e.geometry.location.lat(),longitude:e.geometry.location.lng()},_();const t=e.address_components;let s="";for(const o of t)if(o.types.includes("locality")){s=o.long_name;break}console.log(`City: ${s}`)}else console.error("Place has no geometry")},P=()=>{if(y.value){const e=new google.maps.LatLngBounds,t=new google.maps.LatLng(n.value.lat,n.value.lng),s=l.value*1e3,o=google.maps.geometry.spherical.computeOffset(t,s,45),d=google.maps.geometry.spherical.computeOffset(t,s,225);e.extend(t),e.extend(o),e.extend(d),y.value.fitBounds(e)}},D=e=>{n.value={lat:e.latLng.lat(),lng:e.latLng.lng()},r.value={latitude:e.latLng.lat(),longitude:e.latLng.lng()},_()},L=async(e,t,s,o)=>{try{const d=await O.get(`/api/enterprises/${o}`,{params:{latitude:e,longitude:t,distance:s}});w.value=d.data}catch(d){console.error("Error loading enterprises:",d)}},b=q(async()=>{navigator.geolocation?navigator.geolocation.getCurrentPosition(async e=>{C.value={lat:e.coords.latitude,lng:e.coords.longitude},n.value=C.value,r.value={latitude:e.coords.latitude,longitude:e.coords.longitude},await L(r.value.latitude,r.value.longitude,l.value,p.authUser.id)}):console.error("Geolocation is not supported by this browser.")}),h=c(null),H=()=>{if(h.value)return;const e=document.getElementById("autocomplete");h.value=new google.maps.places.Autocomplete(e,{types:["(cities)"]}),h.value.addListener("place_changed",()=>{const t=h.value.getPlace();if(t.geometry){const s=t.geometry.location.lat(),o=t.geometry.location.lng();A(s,o),console.log(`City: ${t.formatted_address}`)}else console.error("Place has no geometry")})},G=e=>{E.push({name:"index",params:{id:e}})},K=()=>{A(r.value.latitude,r.value.longitude),ne(l.value),E.push({name:"dashboard"})};return(e,t)=>{const s=z("router-link");return u(),m(U,null,[f(s,{to:{name:"dashboard"},class:"link"},{default:x(()=>[se]),_:1}),a("div",re,[f(i(ae),{"api-key":i(B),center:n.value,zoom:10,style:{width:"100%",height:"100%"},onLoad:t[0]||(t[0]=o=>y.value.value=o),onClick:D},{default:x(()=>[(u(!0),m(U,null,Q(w.value,o=>(u(),M(i(R),{key:o.id,options:{center:{lat:parseFloat(o.latitude),lng:parseFloat(o.longitude)},radius:500,fillColor:"#FF0000",fillOpacity:.5,strokeColor:"#FF0000",strokeOpacity:.8,strokeWeight:2},onClick:()=>G(o.id)},null,8,["options","onClick"]))),128)),i(p).authUser.is_seller?I("",!0):(u(),M(i(R),{key:0,options:g.value},null,8,["options"])),f(i(oe),{options:{position:n.value}},null,8,["options"])]),_:1},8,["api-key","center"]),a("form",{onSubmit:t[2]||(t[2]=Y((...o)=>i(b)&&i(b)(...o),["prevent"])),class:"location-form"},[i(p).authUser.is_seller?(u(),m("div",ie,[ce,i(p).authUser.is_seller?(u(),m("button",{key:0,onClick:V},"Ort speichern")):I("",!0),a("button",{onClick:T},"Meinen Standort verwenden")])):(u(),m("div",ue,[a("input",{id:"autocomplete",type:"text",placeholder:"Enter a city or location",onFocus:H},null,32),a("button",{onClick:F},"Stadt suchen"),de,a("div",pe,[j(a("input",{type:"range",min:"1",max:"20","onUpdate:modelValue":t[1]||(t[1]=o=>l.value=o),onInput:_},null,544),[[Z,l.value]]),a("span",null,J(l.value)+" km",1)]),a("button",{onClick:K},"Suchen")]))],32)])],64)}}},ye=N(ge,[["__scopeId","data-v-95f2a300"]]);export{ye as default};
