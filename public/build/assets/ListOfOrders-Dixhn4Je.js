import{_ as P,u as D,r as w,j as U,o as i,c as a,d as e,b as m,F as y,m as $,q as _,t as o,P as c,k as g,l as p,S as b,p as I,g as O}from"./app-oQ4phOeS.js";import{F as z}from"./FooterUser-DYd6C9Gl.js";import{F as V}from"./FooterSeller-Bq56wEJ7.js";/* empty css                                                                       */import"./locals-COUou-VV.js";import{H as E}from"./Header-D5iZQSvk.js";/* empty css                                                                   */import"./CartList.vue_vue_type_style_index_0_scoped_444ccb5a_lang-BPPwYMcs.js";const l=u=>(I("data-v-e3924a61"),u=u(),O(),u),N={class:"orders-page"},L=l(()=>e("div",{class:"order-details"},[e("h3",null,"Bestellungen")],-1)),q={class:"orders-list"},H=["onClick"],K=["src"],Z={key:1,class:"order-details-popup"},j={class:"details-header"},M={class:"details-header"},R=["src"],T=l(()=>e("strong",null,"PDF",-1)),G=l(()=>e("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24px",viewBox:"0 -960 960 960",width:"24px",fill:"#298E46"},[e("path",{d:"m242-200 200-280-200-280h98l200 280-200 280h-98Zm238 0 200-280-200-280h98l200 280-200 280h-98Z"})],-1)),J=l(()=>e("strong",null,"Address:",-1)),Q=l(()=>e("strong",null,"Abholungszeit:",-1)),W=l(()=>e("strong",null,"Bestellungsdatum:",-1)),X=l(()=>e("strong",null,"Bestellungsnummer:",-1)),Y=l(()=>e("strong",null,"Preis:",-1)),ee={key:0},se=l(()=>e("strong",null,"Abgeholt:",-1)),te={key:1,class:"collect-section"},oe=l(()=>e("h3",null,"Bestellung zum Abholen",-1)),le={class:"collect-info"},re=l(()=>e("div",{class:"quantity"},[e("div",{class:"circle"},"1x"),e("p",null,"Surprise Bag")],-1)),ne={class:"order-code"},ie=l(()=>e("p",null,"Drücken Sie den Button zum Abholen. Verkäfer und Käufer beachten.",-1)),ae=l(()=>e("span",{class:"swipe-icon"},"➔",-1)),ce={key:2,class:"collect-section"},de=l(()=>e("h3",null,"Bestellung zum Abholen",-1)),ue={class:"collect-info"},_e=l(()=>e("div",{class:"quantity"},[e("div",{class:"circle"},"1x"),e("p",null,"Surprise Bag")],-1)),pe={class:"order-code"},he=l(()=>e("p",null,"Drücken Sie den Button zum Abholen. Verkäfer und Käufer beachten.",-1)),ve=l(()=>e("span",{class:"swipe-icon"},"➔",-1)),me={key:0},ge={key:1},fe={__name:"ListOfOrders",setup(u){const d=D(),f=d.authUser.id,h=w([]),C=async()=>{try{const t=await p.get(`/api/sellerorder/${f}`);h.value=t.data}catch(t){console.error("Failed to fetch orders:",t)}},B=async()=>{try{const t=await p.get(`/api/userorder/${f}`);h.value=t.data}catch(t){console.error("Failed to fetch orders:",t)}};U(()=>{d.authUser.is_seller?C():B()});const s=w(null),A=t=>{s.value=t},v=()=>{s.value=null},k=async t=>{try{t.picked=!0,await p.put("/api/orders/markAsCollected",{orderId:t.id}),v(),b.fire({toast:!0,icon:"success",position:"top-end",showConfirmButton:!1,title:"Abholen erfolgreich!",timer:3e3})}catch(r){console.error("Error marking order as collected:",r),b.fire({toast:!0,icon:"error",position:"top-end",showConfirmButton:!1,title:"Nicht Aktualisiert! Versuchen Sie wieder"}),t.picked=!1}},F=async t=>{try{const r=await p.post(`/api/orders/pdf/${t}`);r.data.pdfUrl?window.location.href=r.data.pdfUrl:console.error("PDF URL not found in response:",r)}catch(r){console.error("Error generating PDF:",r)}},S=t=>t?`storage/${t}`:"storage/enterprise_images/default.png",x=t=>t?`storage/${t}`:"storage/profile_images/default.png";return(t,r)=>(i(),a(y,null,[e("header",null,[m(E)]),e("div",N,[L,e("div",q,[(i(!0),a(y,null,$(h.value,n=>(i(),a("div",{key:n.id,class:"order-item",onClick:ke=>A(n)},[e("img",{src:x(n.profile_image),alt:"Profil Image"},null,8,K),e("div",null,[e("h3",null,o(n.firstname)+" "+o(n.lastname),1),e("p",null,o(n.street)+" "+o(n.house_number)+", "+o(n.zip_code)+" "+o(n.city),1)])],8,H))),128))]),s.value?(i(),a("div",{key:0,class:"backdrop",onClick:v})):_("",!0),s.value?(i(),a("div",Z,[e("div",j,[e("h2",null,o(s.value.firstname)+" "+o(s.value.lastname),1),e("button",{onClick:v},"Close")]),e("div",M,[e("img",{src:S(s.value.enterprise_image),alt:"Image"},null,8,R),e("p",{onClick:r[0]||(r[0]=n=>F(s.value.id))},[T,c(),G])]),e("p",null,[J,c(" "+o(s.value.street)+" "+o(s.value.house_number)+", "+o(s.value.zip_code)+" "+o(s.value.city),1)]),e("p",null,[Q,c(" von "+o(s.value.opening)+" bis "+o(s.value.closing),1)]),e("p",null,[W,c(" "+o(s.value.orderDate),1)]),e("p",null,[X,c(" "+o(s.value.id),1)]),e("p",null,[Y,c(" "+o(s.value.price)+" CHF",1)]),s.value.picked?(i(),a("div",ee,[e("p",null,[se,c(" "+o(s.value.picked),1)])])):_("",!0),!s.value.picked&&s.value.user_id===1&&g(d).authUser.is_seller?(i(),a("div",te,[oe,e("div",le,[re,e("p",null,o(s.value.firstname),1),e("div",ne,o(s.value.orderCode),1)]),ie,e("button",{class:"swipe-to-collect",onClick:r[1]||(r[1]=n=>k(s.value))},[ae,c(" Abholen ")])])):!s.value.picked&&!g(d).authUser.is_seller?(i(),a("div",ce,[de,e("div",ue,[_e,e("p",null,o(s.value.firstname),1),e("div",pe,o(s.value.orderCode),1)]),he,e("button",{class:"swipe-to-collect",onClick:r[2]||(r[2]=n=>k(s.value))},[ve,c(" Abholen ")])])):_("",!0)])):_("",!0)]),g(d).authUser.is_seller?(i(),a("footer",me,[m(V)])):(i(),a("footer",ge,[m(z)]))],64))}},xe=P(fe,[["__scopeId","data-v-e3924a61"]]);export{xe as default};