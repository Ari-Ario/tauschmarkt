import{_ as H,r as d,u as M,j as O,a as Z,o as a,c as n,d as s,b as h,F as C,m as K,q as b,k as E,e as $,w as A,t as w,f as V,v as U,l as S,p as D,g as G,G as L}from"./app-B5EnG7Dm.js";import{F as Q}from"./FooterUser-CVivNSH6.js";import{F as R}from"./FooterSeller-BMs7xH-6.js";import{H as J}from"./Header-D2I_K_Vb.js";/* empty css                                                                   */import"./CartList.vue_vue_type_style_index_0_scoped_444ccb5a_lang-DXUaFK7H.js";const _=p=>(D("data-v-1021cd95"),p=p(),G(),p),W={class:"container"},X={class:"profile-page"},Y=_(()=>s("div",{class:"account-details"},[s("h3",null,"Meine Profil")],-1)),ee=["onClick"],te={class:"profile-value"},se=_(()=>s("span",null,"Betriebsort fehlt",-1)),oe=_(()=>s("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24px",viewBox:"0 -960 960 960",width:"24px",fill:"#298E46"},[s("path",{d:"M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"})],-1)),ae={key:2},ne={key:3},re={key:4,class:"edit-popup"},ie=["for"],le=["id"],ue=["id"],ce=_(()=>s("button",{type:"submit",style:{"margin-bottom":"10px"}},"Speichern",-1)),de={__name:"ProfileInformation",setup(p){var B;d([]);const g=d(!1),o=d(""),r=d(""),c=d({}),y=d({}),m={firstname:"Vorname",lastname:"Nachname",email:"Email",street:"Strasse",house_number:"Hausnummer",city:"Stadt",zip_code:"Postleitzahl",opening:"öffnungszeit",closing:"Betriebsschluss",payment:"Bankkonto"},i=M();L();const q=(B=i==null?void 0:i.authUser)==null?void 0:B.id,P=async()=>{try{const e=await(await S.get(`api/user/profile/${q}`)).data;c.value={Vorname:e.firstname,Nachname:e.lastname,Strasse:e.street,Hausnummer:e.house_number,Stadt:e.city,Postleitzahl:e.zip_code,öffnungszeit:e.opening,Betriebsschluss:e.closing,Bankkonto:e.payment},y.value={latitude:e.latitude,longitude:e.longitude}}catch(t){console.error("Error loading blogs:",t)}},F=async()=>{try{const e=await(await S.get(`api/user/profile/${q}`)).data;c.value={Vorname:e.firstname,Nachname:e.lastname,Strasse:e.street,Hausnummer:e.house_number,Stadt:e.city,Postleitzahl:e.zip_code}}catch(t){console.error("Error loading blogs:",t)}};O(()=>{var t;(t=i==null?void 0:i.authUser)!=null&&t.is_seller?P():F()});const T=(t,e)=>{o.value=e,r.value=t,g.value=!0},N=async()=>{if(o.value!==null&&Object.keys(m).find(e=>m[e]===o.value)){c.value[o.value]=r.value;const e=Object.fromEntries(Object.entries(c.value).map(([u,f])=>[Object.keys(m).find(v=>m[v]===u),f]));try{const u=await S.put("/api/user/update-profile",e);console.log("Profile updated:",u.data)}catch(u){console.error("Error updating profile:",u)}}x()},x=()=>{g.value=!1,o.value={},r.value=""},j=t=>t.split(":").length===2?`${t}:00`:t;return(t,e)=>{var f,v,I,z;const u=Z("router-link");return a(),n(C,null,[s("header",null,[h(J)]),s("div",W,[s("div",X,[Y,c.value?(a(!0),n(C,{key:0},K(c.value,(l,k)=>(a(),n("div",{class:"profile-section",key:k},[s("div",{class:"profile-item",onClick:()=>T(l,k)},[s("span",null,w(k),1),s("span",te,w(l),1)],8,ee)]))),128)):b("",!0),(v=(f=E(i))==null?void 0:f.authUser)!=null&&v.is_seller&&!y.value.latitude&&!y.value.longitude?(a(),n("div",{key:1,class:"profile-item",onClick:e[0]||(e[0]=()=>T(t.value,t.key))},[se,h(u,{to:{name:"map"},class:"link"},{default:$(()=>[oe]),_:1})])):b("",!0),(z=(I=E(i))==null?void 0:I.authUser)!=null&&z.is_seller?(a(),n("div",ae,[h(R)])):(a(),n("div",ne,[h(Q)])),g.value?(a(),n("div",re,[s("form",{onSubmit:A(N,["prevent"])},[s("label",{for:o.value},w(o.value),9,ie),o.value==="öffnungszeit"||o.value==="Betriebsschluss"?V((a(),n("input",{key:0,type:"time","onUpdate:modelValue":e[1]||(e[1]=l=>r.value=l),id:o.value,required:"",onChange:e[2]||(e[2]=l=>r.value=j(l.target.value))},null,40,le)),[[U,r.value]]):V((a(),n("input",{key:1,type:"text","onUpdate:modelValue":e[3]||(e[3]=l=>r.value=l),id:o.value,required:""},null,8,ue)),[[U,r.value]]),ce,s("button",{type:"button",onClick:x},"Ablehnen")],32)])):b("",!0)])])],64)}}},ge=H(de,[["__scopeId","data-v-1021cd95"]]);export{ge as default};
