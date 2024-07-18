import{H as y,r as v,j as Q,I as Xe,J as be,K as A,F as K,L as P,x as X,M,N as xe,o as O,c as j,d as m,_ as tt,u as nt,i as se,b as ne,e as ae,k as H,n as Be,m as he,f as at,O as lt,t as W,q as le,P as ot,R as st,l as Re,B as Ue,p as it,g as rt,Q as L,T as ut}from"./app-DWDepuyr.js";/* empty css                                                                       */import{l as oe,d as ct}from"./locals-C3iIwN0i.js";let dt=Symbol("headlessui.useid"),pt=0;function He(){return y(dt,()=>`${++pt}`)()}function N(e){var t;if(e==null||e.value==null)return null;let n=(t=e.value.$el)!=null?t:e.value;return n instanceof Node?n:null}function ie(e,t,...n){if(e in t){let a=t[e];return typeof a=="function"?a(...n):a}let l=new Error(`Tried to handle "${e}" but there is no handler defined. Only defined handlers are: ${Object.keys(t).map(a=>`"${a}"`).join(", ")}.`);throw Error.captureStackTrace&&Error.captureStackTrace(l,ie),l}function Ve(e,t){if(e)return e;let n=t??"button";if(typeof n=="string"&&n.toLowerCase()==="button")return"button"}function vt(e,t){let n=v(Ve(e.value.type,e.value.as));return Q(()=>{n.value=Ve(e.value.type,e.value.as)}),Xe(()=>{var l;n.value||N(t)&&N(t)instanceof HTMLButtonElement&&!((l=N(t))!=null&&l.hasAttribute("type"))&&(n.value="button")}),n}var we=(e=>(e[e.None=0]="None",e[e.RenderStrategy=1]="RenderStrategy",e[e.Static=2]="Static",e))(we||{}),ft=(e=>(e[e.Unmount=0]="Unmount",e[e.Hidden=1]="Hidden",e))(ft||{});function ke({visible:e=!0,features:t=0,ourProps:n,theirProps:l,...a}){var s;let i=Fe(l,n),r=Object.assign(a,{props:i});if(e||t&2&&i.static)return me(r);if(t&1){let d=(s=i.unmount)==null||s?0:1;return ie(d,{0(){return null},1(){return me({...a,props:{...i,hidden:!0,style:{display:"none"}}})}})}return me(r)}function me({props:e,attrs:t,slots:n,slot:l,name:a}){var s,i;let{as:r,...d}=gt(e,["unmount","static"]),o=(s=n.default)==null?void 0:s.call(n,l),h={};if(l){let w=!1,u=[];for(let[S,T]of Object.entries(l))typeof T=="boolean"&&(w=!0),T===!0&&u.push(S);w&&(h["data-headlessui-state"]=u.join(" "))}if(r==="template"){if(o=Ye(o??[]),Object.keys(d).length>0||Object.keys(t).length>0){let[w,...u]=o??[];if(!ht(w)||u.length>0)throw new Error(['Passing props on "template"!',"",`The current component <${a} /> is rendering a "template".`,"However we need to passthrough the following props:",Object.keys(d).concat(Object.keys(t)).map(f=>f.trim()).filter((f,p,b)=>b.indexOf(f)===p).sort((f,p)=>f.localeCompare(p)).map(f=>`  - ${f}`).join(`
`),"","You can apply a few solutions:",['Add an `as="..."` prop, to ensure that we render an actual element instead of a "template".',"Render a single element as the child so that we can forward the props onto that element."].map(f=>`  - ${f}`).join(`
`)].join(`
`));let S=Fe((i=w.props)!=null?i:{},d,h),T=be(w,S,!0);for(let f in S)f.startsWith("on")&&(T.props||(T.props={}),T.props[f]=S[f]);return T}return Array.isArray(o)&&o.length===1?o[0]:o}return A(r,Object.assign({},d,h),{default:()=>o})}function Ye(e){return e.flatMap(t=>t.type===K?Ye(t.children):[t])}function Fe(...e){if(e.length===0)return{};if(e.length===1)return e[0];let t={},n={};for(let l of e)for(let a in l)a.startsWith("on")&&typeof l[a]=="function"?(n[a]!=null||(n[a]=[]),n[a].push(l[a])):t[a]=l[a];if(t.disabled||t["aria-disabled"])return Object.assign(t,Object.fromEntries(Object.keys(n).map(l=>[l,void 0])));for(let l in n)Object.assign(t,{[l](a,...s){let i=n[l];for(let r of i){if(a instanceof Event&&a.defaultPrevented)return;r(a,...s)}}});return t}function gt(e,t=[]){let n=Object.assign({},e);for(let l of t)l in n&&delete n[l];return n}function ht(e){return e==null?!1:typeof e.type=="string"||typeof e.type=="object"||typeof e.type=="function"}let We=Symbol("Context");var G=(e=>(e[e.Open=1]="Open",e[e.Closed=2]="Closed",e[e.Closing=4]="Closing",e[e.Opening=8]="Opening",e))(G||{});function mt(){return y(We,null)}function bt(e){P(We,e)}var z=(e=>(e.Space=" ",e.Enter="Enter",e.Escape="Escape",e.Backspace="Backspace",e.Delete="Delete",e.ArrowLeft="ArrowLeft",e.ArrowUp="ArrowUp",e.ArrowRight="ArrowRight",e.ArrowDown="ArrowDown",e.Home="Home",e.End="End",e.PageUp="PageUp",e.PageDown="PageDown",e.Tab="Tab",e))(z||{}),wt=(e=>(e[e.Open=0]="Open",e[e.Closed=1]="Closed",e))(wt||{});let Ke=Symbol("DisclosureContext");function Ce(e){let t=y(Ke,null);if(t===null){let n=new Error(`<${e} /> is missing a parent <Disclosure /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Ce),n}return t}let Ge=Symbol("DisclosurePanelContext");function yt(){return y(Ge,null)}let _t=X({name:"Disclosure",props:{as:{type:[Object,String],default:"template"},defaultOpen:{type:[Boolean],default:!1}},setup(e,{slots:t,attrs:n}){let l=v(e.defaultOpen?0:1),a=v(null),s=v(null),i={buttonId:v(`headlessui-disclosure-button-${He()}`),panelId:v(`headlessui-disclosure-panel-${He()}`),disclosureState:l,panel:a,button:s,toggleDisclosure(){l.value=ie(l.value,{0:1,1:0})},closeDisclosure(){l.value!==1&&(l.value=1)},close(r){i.closeDisclosure();let d=r?r instanceof HTMLElement?r:r.value instanceof HTMLElement?N(r):N(i.button):N(i.button);d==null||d.focus()}};return P(Ke,i),bt(M(()=>ie(l.value,{0:G.Open,1:G.Closed}))),()=>{let{defaultOpen:r,...d}=e,o={open:l.value===0,close:i.close};return ke({theirProps:d,ourProps:{},slot:o,slots:t,attrs:n,name:"Disclosure"})}}}),St=X({name:"DisclosureButton",props:{as:{type:[Object,String],default:"button"},disabled:{type:[Boolean],default:!1},id:{type:String,default:null}},setup(e,{attrs:t,slots:n,expose:l}){let a=Ce("DisclosureButton"),s=yt(),i=M(()=>s===null?!1:s.value===a.panelId.value);Q(()=>{i.value||e.id!==null&&(a.buttonId.value=e.id)}),xe(()=>{i.value||(a.buttonId.value=null)});let r=v(null);l({el:r,$el:r}),i.value||Xe(()=>{a.button.value=r.value});let d=vt(M(()=>({as:e.as,type:t.type})),r);function o(){var u;e.disabled||(i.value?(a.toggleDisclosure(),(u=N(a.button))==null||u.focus()):a.toggleDisclosure())}function h(u){var S;if(!e.disabled)if(i.value)switch(u.key){case z.Space:case z.Enter:u.preventDefault(),u.stopPropagation(),a.toggleDisclosure(),(S=N(a.button))==null||S.focus();break}else switch(u.key){case z.Space:case z.Enter:u.preventDefault(),u.stopPropagation(),a.toggleDisclosure();break}}function w(u){switch(u.key){case z.Space:u.preventDefault();break}}return()=>{var u;let S={open:a.disclosureState.value===0},{id:T,...f}=e,p=i.value?{ref:r,type:d.value,onClick:o,onKeydown:h}:{id:(u=a.buttonId.value)!=null?u:T,ref:r,type:d.value,"aria-expanded":a.disclosureState.value===0,"aria-controls":a.disclosureState.value===0||N(a.panel)?a.panelId.value:void 0,disabled:e.disabled?!0:void 0,onClick:o,onKeydown:h,onKeyup:w};return ke({ourProps:p,theirProps:f,slot:S,attrs:t,slots:n,name:"DisclosureButton"})}}}),xt=X({name:"DisclosurePanel",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},id:{type:String,default:null}},setup(e,{attrs:t,slots:n,expose:l}){let a=Ce("DisclosurePanel");Q(()=>{e.id!==null&&(a.panelId.value=e.id)}),xe(()=>{a.panelId.value=null}),l({el:a.panel,$el:a.panel}),P(Ge,a.panelId);let s=mt(),i=M(()=>s!==null?(s.value&G.Open)===G.Open:a.disclosureState.value===0);return()=>{var r;let d={open:a.disclosureState.value===0,close:a.close},{id:o,...h}=e,w={id:(r=a.panelId.value)!=null?r:o,ref:a.panel};return ke({ourProps:w,theirProps:h,slot:d,attrs:t,slots:n,features:we.RenderStrategy|we.Static,visible:i.value,name:"DisclosurePanel"})}}});function kt(e,t){return O(),j("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true","data-slot":"icon"},[m("path",{"fill-rule":"evenodd",d:"M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z","clip-rule":"evenodd"})])}function Ct(e,t){return O(),j("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true","data-slot":"icon"},[m("path",{d:"M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"})])}const V=e=>(it("data-v-a40177cd"),e=e(),rt(),e),Ot={class:"enterprises"},At=V(()=>m("h1",{style:{"text-align":"center",color:"#004d40"}},"Tauschmarkt",-1)),Tt={class:"-my-3 flow-root"},Et=V(()=>m("span",{class:"font-medium text-gray-900"},"Categories",-1)),jt={class:"ml-6 flex items-center"},Dt={class:"space-y-4"},Pt=["id","value"],It=["for"],Mt={class:"Photo"},$t=["src"],Nt=["onClick"],Lt={key:0,xmlns:"http://www.w3.org/2000/svg",fill:"red",viewBox:"0 0 24 24",width:"24px",height:"24px"},Bt=V(()=>m("path",{d:"M0 0h24v24H0z",fill:"none"},null,-1)),Rt=V(()=>m("path",{d:"M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"},null,-1)),Ut=[Bt,Rt],Ht={key:1,xmlns:"http://www.w3.org/2000/svg",fill:"green",viewBox:"0 0 24 24",width:"24px",height:"24px"},Vt=V(()=>m("path",{d:"M0 0h24v24H0z",fill:"none"},null,-1)),zt=V(()=>m("path",{d:"M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"},null,-1)),Xt=[Vt,zt],Yt={class:"details"},Ft={class:"user-details"},Wt={class:"user-photo"},Kt=["src"],Gt={class:"user-name description"},Qt={key:0},Zt=["onClick"],qt={key:0},Jt={key:1,class:"popup"},en=["onClick"],tn=V(()=>m("div",{class:"link",id:"enterpriseButton"}," Besuchen ",-1)),nn=[tn],an={__name:"EnterpriseCard",setup(e){var f;const t=nt(),n=(f=t==null?void 0:t.authUser)==null?void 0:f.id,l=v([]),a=v([]),s=v([]),i=v([]),r=async(p,b,g,E)=>{try{const k=await Re.get(`/api/enterprises/${E}`,{params:{latitude:oe.value.latitude,longitude:oe.value.longitude,distance:g.value}});l.value=k.data,d()}catch(k){console.error("Error loading enterprises:",k)}try{const k=await Re.get("/api/product/1");s.value=k.data.categories}catch(k){console.error("Failed to fetch Product:",k)}},d=()=>{i.value.length===0?a.value=l.value:a.value=l.value.filter(p=>p.categories?p.categories.some(b=>i.value.includes(b)):(console.warn(`Enterprise ${p.id} has no categories`),!1))};se(i,d);const o=async p=>{try{if(p.is_favorite=!p.is_favorite,p.is_favorite){let b=await Ue.post("/favorites/add",{enterpriseId:p.id,userId:t.authUser.id,isFavorite:p.is_favorite})}else{let b=await Ue.post("/favorites/remove",{enterpriseId:p.id,userId:t.authUser.id,isFavorite:p.is_favorite})}}catch(b){console.error("Error adding to favorites:",b)}},h=v({}),w=p=>{h.value[p]=!h.value[p]},u=p=>p.categories.map(b=>{var g;return(g=s.value.find(E=>E.id===b))==null?void 0:g.name}).filter(b=>b);Q(()=>{r(oe.latitude,oe.longitude,ct,n)});const S=p=>p?`storage/${p}`:"storage/enterprise_images/default.png",T=p=>p?`storage/${p}`:"storage/profile_images/default.png";return(p,b)=>(O(),j("div",Ot,[At,ne(H(_t),{as:"div",class:"border-b border-gray-200 py-6"},{default:ae(({open:g})=>[m("h3",Tt,[ne(H(St),{class:"flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500",style:{height:"40px",display:"flex","justify-content":"space-between"}},{default:ae(()=>[Et,m("span",jt,[g?(O(),Be(H(kt),{key:1,class:"h-5 w-5","aria-hidden":"true",style:{height:"40px"}})):(O(),Be(H(Ct),{key:0,class:"h-5 w-5","aria-hidden":"true",style:{height:"40px"}}))])]),_:2},1024)]),ne(H(xt),{class:"pt-6 dropdown"},{default:ae(()=>[m("div",Dt,[(O(!0),j(K,null,he(s.value,E=>(O(),j("div",{key:E.id,class:"flex items-center"},[at(m("input",{id:`filter-${E.id}`,value:E.id,type:"checkbox","onUpdate:modelValue":b[0]||(b[0]=k=>i.value=k),class:"h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"},null,8,Pt),[[lt,i.value]]),m("label",{for:`filter-${E.id}`,class:"ml-3 text-sm text-gray-600"},W(E.name),9,It)]))),128))])]),_:1})]),_:1}),(O(!0),j(K,null,he(a.value,g=>{var E,k;return O(),j("div",{class:"card",key:g.id},[m("div",Mt,[m("img",{class:"Photo",src:S(g.enterprise_picture),alt:""},null,8,$t),(k=(E=H(t))==null?void 0:E.authUser)!=null&&k.is_seller?le("",!0):(O(),j("div",{key:0,class:"favorite",onClick:I=>o(g)},[g.is_favorite?(O(),j("svg",Lt,Ut)):(O(),j("svg",Ht,Xt))],8,Nt))]),m("div",Yt,[m("div",Ft,[m("div",Wt,[m("img",{class:"user-photo",src:T(g.profile_picture)},null,8,Kt)]),m("h3",Gt,W(g.firstname)+" "+W(g.lastname),1),g.categories.length>0?(O(),j("div",Qt,[m("label",{onClick:I=>w(g.id),class:"category-label"},[ot(W(u(g)[0])+" ",1),g.categories.length>1?(O(),j("span",qt," + ")):le("",!0)],8,Zt)])):le("",!0),h.value[g.id]&&g.categories.length>1?(O(),j("div",Jt,[m("ul",null,[(O(!0),j(K,null,he(u(g).slice(1),I=>(O(),j("li",{key:I},W(I),1))),128))])])):le("",!0),m("div",{onClick:I=>p.openEnterprise(g.id),class:"enterprise-btn"},[ne(H(st),{to:{name:"index",params:{id:g.id}},"customv-slot":"{ navigate }"},{default:ae(()=>[m("div",{class:"link",onClick:b[1]||(b[1]=(...I)=>p.navigate&&p.navigate(...I)),role:"link"},nn)]),_:2},1032,["to"])],8,en)])])])}),128))]))}},hn=tt(an,[["__scopeId","data-v-a40177cd"]]);/**
 * Vue 3 Carousel 0.3.3
 * (c) 2024
 * @license MIT
 */const _={itemsToShow:1,itemsToScroll:1,modelValue:0,transition:300,autoplay:0,snapAlign:"center",wrapAround:!1,throttle:16,pauseAutoplayOnHover:!1,mouseDrag:!0,touchDrag:!0,dir:"ltr",breakpoints:void 0,i18n:{ariaNextSlide:"Navigate to next slide",ariaPreviousSlide:"Navigate to previous slide",ariaNavigateToSlide:"Navigate to slide {slideNumber}",ariaGallery:"Gallery",itemXofY:"Item {currentSlide} of {slidesCount}",iconArrowUp:"Arrow pointing upwards",iconArrowDown:"Arrow pointing downwards",iconArrowRight:"Arrow pointing to the right",iconArrowLeft:"Arrow pointing to the left"}},ze={itemsToShow:{default:_.itemsToShow,type:Number},itemsToScroll:{default:_.itemsToScroll,type:Number},wrapAround:{default:_.wrapAround,type:Boolean},throttle:{default:_.throttle,type:Number},snapAlign:{default:_.snapAlign,validator(e){return["start","end","center","center-even","center-odd"].includes(e)}},transition:{default:_.transition,type:Number},breakpoints:{default:_.breakpoints,type:Object},autoplay:{default:_.autoplay,type:Number},pauseAutoplayOnHover:{default:_.pauseAutoplayOnHover,type:Boolean},modelValue:{default:void 0,type:Number},mouseDrag:{default:_.mouseDrag,type:Boolean},touchDrag:{default:_.touchDrag,type:Boolean},dir:{default:_.dir,validator(e){return["rtl","ltr"].includes(e)}},i18n:{default:_.i18n,type:Object},settings:{default(){return{}},type:Object}};function ln({config:e,slidesCount:t}){const{snapAlign:n,wrapAround:l,itemsToShow:a=1}=e;if(l)return Math.max(t-1,0);let s;switch(n){case"start":s=t-a;break;case"end":s=t-1;break;case"center":case"center-odd":s=t-Math.ceil((a-.5)/2);break;case"center-even":s=t-Math.ceil(a/2);break;default:s=0;break}return Math.max(s,0)}function on({config:e,slidesCount:t}){const{wrapAround:n,snapAlign:l,itemsToShow:a=1}=e;let s=0;if(n||a>t)return s;switch(l){case"start":s=0;break;case"end":s=a-1;break;case"center":case"center-odd":s=Math.floor((a-1)/2);break;case"center-even":s=Math.floor((a-2)/2);break;default:s=0;break}return s}function ye({val:e,max:t,min:n}){return t<n?e:Math.min(Math.max(e,n),t)}function sn({config:e,currentSlide:t,slidesCount:n}){const{snapAlign:l,wrapAround:a,itemsToShow:s=1}=e;let i=t;switch(l){case"center":case"center-odd":i-=(s-1)/2;break;case"center-even":i-=(s-2)/2;break;case"end":i-=s-1;break}return a?i:ye({val:i,max:n-s,min:0})}function Qe(e){return e?e.reduce((t,n)=>{var l;return n.type===K?[...t,...Qe(n.children)]:((l=n.type)===null||l===void 0?void 0:l.name)==="CarouselSlide"?[...t,n]:t},[]):[]}function re({val:e,max:t,min:n=0}){return e>t?re({val:e-(t+1),max:t,min:n}):e<n?re({val:e+(t+1),max:t,min:n}):e}function rn(e,t){let n;return t?function(...l){const a=this;n||(e.apply(a,l),n=!0,setTimeout(()=>n=!1,t))}:e}function un(e,t){let n;return function(...l){n&&clearTimeout(n),n=setTimeout(()=>{e(...l),n=null},t)}}function Ze(e="",t={}){return Object.entries(t).reduce((n,[l,a])=>n.replace(`{${l}}`,String(a)),e)}var cn=X({name:"ARIA",setup(){const e=y("config",L(Object.assign({},_))),t=y("currentSlide",v(0)),n=y("slidesCount",v(0));return()=>A("div",{class:["carousel__liveregion","carousel__sr-only"],"aria-live":"polite","aria-atomic":"true"},Ze(e.i18n.itemXofY,{currentSlide:t.value+1,slidesCount:n.value}))}}),mn=X({name:"Carousel",props:ze,setup(e,{slots:t,emit:n,expose:l}){var a;const s=v(null),i=v([]),r=v(0),d=v(0),o=L(Object.assign({},_));let h=Object.assign({},_),w;const u=v((a=e.modelValue)!==null&&a!==void 0?a:0),S=v(0),T=v(0),f=v(0),p=v(0);let b,g;P("config",o),P("slidesCount",d),P("currentSlide",u),P("maxSlide",f),P("minSlide",p),P("slideWidth",r);function E(){w=Object.assign({},e.breakpoints),h=Object.assign(Object.assign(Object.assign({},h),e),{i18n:Object.assign(Object.assign({},h.i18n),e.i18n),breakpoints:void 0}),I(h)}function k(){if(!w||!Object.keys(w).length)return;const c=Object.keys(w).map(x=>Number(x)).sort((x,$)=>+$-+x);let C=Object.assign({},h);c.some(x=>{const $=window.matchMedia(`(min-width: ${x}px)`).matches;return $&&(C=Object.assign(Object.assign({},C),w[x])),$}),I(C)}function I(c){Object.entries(c).forEach(([C,x])=>o[C]=x)}const Oe=un(()=>{k(),Z(),Y()},16);function Y(){if(!s.value)return;const c=s.value.getBoundingClientRect();r.value=c.width/o.itemsToShow}function Z(){d.value<=0||(T.value=Math.ceil((d.value-1)/2),f.value=ln({config:o,slidesCount:d.value}),p.value=on({config:o,slidesCount:d.value}),o.wrapAround||(u.value=ye({val:u.value,max:f.value,min:p.value})))}Q(()=>{ut(()=>Y()),setTimeout(()=>Y(),1e3),k(),je(),window.addEventListener("resize",Oe,{passive:!0}),n("init")}),xe(()=>{g&&clearTimeout(g),b&&clearInterval(b),window.removeEventListener("resize",Oe,{passive:!0})});let D=!1;const q={x:0,y:0},J={x:0,y:0},B=L({x:0,y:0}),ee=v(!1),ue=v(!1),qe=()=>{ee.value=!0},Je=()=>{ee.value=!1};function Ae(c){["INPUT","TEXTAREA","SELECT"].includes(c.target.tagName)||(D=c.type==="touchstart",D||c.preventDefault(),!(!D&&c.button!==0||R.value)&&(q.x=D?c.touches[0].clientX:c.clientX,q.y=D?c.touches[0].clientY:c.clientY,document.addEventListener(D?"touchmove":"mousemove",Te,!0),document.addEventListener(D?"touchend":"mouseup",Ee,!0)))}const Te=rn(c=>{ue.value=!0,J.x=D?c.touches[0].clientX:c.clientX,J.y=D?c.touches[0].clientY:c.clientY;const C=J.x-q.x,x=J.y-q.y;B.y=x,B.x=C},o.throttle);function Ee(){const c=o.dir==="rtl"?-1:1,C=Math.sign(B.x)*.4,x=Math.round(B.x/r.value+C)*c;if(x&&!D){const $=Le=>{window.removeEventListener("click",$,!0)};window.addEventListener("click",$,!0)}U(u.value-x),B.x=0,B.y=0,ue.value=!1,document.removeEventListener(D?"touchmove":"mousemove",Te,!0),document.removeEventListener(D?"touchend":"mouseup",Ee,!0)}function je(){!o.autoplay||o.autoplay<=0||(b=setInterval(()=>{o.pauseAutoplayOnHover&&ee.value||te()},o.autoplay))}function De(){b&&(clearInterval(b),b=null),je()}const R=v(!1);function U(c){const C=o.wrapAround?c:ye({val:c,max:f.value,min:p.value});u.value===C||R.value||(n("slide-start",{slidingToIndex:c,currentSlideIndex:u.value,prevSlideIndex:S.value,slidesCount:d.value}),R.value=!0,S.value=u.value,u.value=C,g=setTimeout(()=>{if(o.wrapAround){const x=re({val:C,max:f.value,min:0});x!==u.value&&(u.value=x,n("loop",{currentSlideIndex:u.value,slidingToIndex:c}))}n("update:modelValue",u.value),n("slide-end",{currentSlideIndex:u.value,prevSlideIndex:S.value,slidesCount:d.value}),R.value=!1,De()},o.transition))}function te(){U(u.value+o.itemsToScroll)}function ce(){U(u.value-o.itemsToScroll)}const Pe={slideTo:U,next:te,prev:ce};P("nav",Pe),P("isSliding",R);const Ie=M(()=>sn({config:o,currentSlide:u.value,slidesCount:d.value}));P("slidesToScroll",Ie);const et=M(()=>{const c=o.dir==="rtl"?-1:1,C=Ie.value*r.value*c;return{transform:`translateX(${B.x-C}px)`,transition:`${R.value?o.transition:0}ms`,margin:o.wrapAround?`0 -${d.value*r.value}px`:"",width:"100%"}});function Me(){E(),k(),Z(),Y(),De()}Object.keys(ze).forEach(c=>{["modelValue"].includes(c)||se(()=>e[c],Me)}),se(()=>e.modelValue,c=>{c!==u.value&&U(Number(c))}),se(d,Z),n("before-init"),E();const $e={config:o,slidesCount:d,slideWidth:r,next:te,prev:ce,slideTo:U,currentSlide:u,maxSlide:f,minSlide:p,middleSlide:T};l({updateBreakpointsConfigs:k,updateSlidesData:Z,updateSlideWidth:Y,initDefaultConfigs:E,restartCarousel:Me,slideTo:U,next:te,prev:ce,nav:Pe,data:$e});const de=t.default||t.slides,pe=t.addons,Ne=L($e);return()=>{const c=Qe(de==null?void 0:de(Ne)),C=(pe==null?void 0:pe(Ne))||[];c.forEach((ve,fe)=>ve.props.index=fe);let x=c;if(o.wrapAround){const ve=c.map((ge,F)=>be(ge,{index:-c.length+F,isClone:!0,key:`clone-before-${F}`})),fe=c.map((ge,F)=>be(ge,{index:c.length+F,isClone:!0,key:`clone-after-${F}`}));x=[...ve,...c,...fe]}i.value=c,d.value=Math.max(c.length,1);const $=A("ol",{class:"carousel__track",style:et.value,onMousedownCapture:o.mouseDrag?Ae:null,onTouchstartPassiveCapture:o.touchDrag?Ae:null},x),Le=A("div",{class:"carousel__viewport"},$);return A("section",{ref:s,class:{carousel:!0,"is-sliding":R.value,"is-dragging":ue.value,"is-hover":ee.value,"carousel--rtl":o.dir==="rtl"},dir:o.dir,"aria-label":o.i18n.ariaGallery,tabindex:"0",onMouseenter:qe,onMouseleave:Je},[Le,C,A(cn)])}}}),_e;(function(e){e.arrowUp="arrowUp",e.arrowDown="arrowDown",e.arrowRight="arrowRight",e.arrowLeft="arrowLeft"})(_e||(_e={}));const dn={arrowUp:"M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z",arrowDown:"M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z",arrowRight:"M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z",arrowLeft:"M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"};function pn(e){return e in _e}const Se=e=>{const t=y("config",L(Object.assign({},_))),n=String(e.name),l=`icon${n.charAt(0).toUpperCase()+n.slice(1)}`;if(!n||typeof n!="string"||!pn(n))return;const a=dn[n],s=A("path",{d:a}),i=t.i18n[l]||e.title||n,r=A("title",i);return A("svg",{class:"carousel__icon",viewBox:"0 0 24 24",role:"img","aria-label":i},[r,s])};Se.props={name:String,title:String};const bn=(e,{slots:t,attrs:n})=>{const{next:l,prev:a}=t||{},s=y("config",L(Object.assign({},_))),i=y("maxSlide",v(1)),r=y("minSlide",v(1)),d=y("currentSlide",v(1)),o=y("nav",{}),{dir:h,wrapAround:w,i18n:u}=s,S=h==="rtl",T=A("button",{type:"button",class:["carousel__prev",!w&&d.value<=r.value&&"carousel__prev--disabled",n==null?void 0:n.class],"aria-label":u.ariaPreviousSlide,onClick:o.prev},(a==null?void 0:a())||A(Se,{name:S?"arrowRight":"arrowLeft"})),f=A("button",{type:"button",class:["carousel__next",!w&&d.value>=i.value&&"carousel__next--disabled",n==null?void 0:n.class],"aria-label":u.ariaNextSlide,onClick:o.next},(l==null?void 0:l())||A(Se,{name:S?"arrowLeft":"arrowRight"}));return[T,f]},wn=()=>{const e=y("config",L(Object.assign({},_))),t=y("maxSlide",v(1)),n=y("minSlide",v(1)),l=y("currentSlide",v(1)),a=y("nav",{}),s=r=>re({val:l.value,max:t.value,min:0})===r,i=[];for(let r=n.value;r<t.value+1;r++){const d=A("button",{type:"button",class:{"carousel__pagination-button":!0,"carousel__pagination-button--active":s(r)},"aria-label":Ze(e.i18n.ariaNavigateToSlide,{slideNumber:r+1}),onClick:()=>a.slideTo(r)}),o=A("li",{class:"carousel__pagination-item",key:r},d);i.push(o)}return A("ol",{class:"carousel__pagination"},i)};var yn=X({name:"CarouselSlide",props:{index:{type:Number,default:1},isClone:{type:Boolean,default:!1}},setup(e,{slots:t}){const n=y("config",L(Object.assign({},_))),l=y("currentSlide",v(0)),a=y("slidesToScroll",v(0)),s=y("isSliding",v(!1)),i=M(()=>e.index===l.value),r=M(()=>e.index===l.value-1),d=M(()=>e.index===l.value+1),o=M(()=>{const h=Math.floor(a.value),w=Math.ceil(a.value+n.itemsToShow-1);return e.index>=h&&e.index<=w});return()=>{var h;return A("li",{style:{width:`${100/n.itemsToShow}%`},class:{carousel__slide:!0,"carousel__slide--clone":e.isClone,"carousel__slide--visible":o.value,"carousel__slide--active":i.value,"carousel__slide--prev":r.value,"carousel__slide--next":d.value,"carousel__slide--sliding":s.value},"aria-hidden":!o.value},(h=t.default)===null||h===void 0?void 0:h.call(t,{isActive:i.value,isClone:e.isClone,isPrev:r.value,isNext:d.value,isSliding:s.value,isVisible:o.value}))}}});export{mn as C,hn as E,bn as N,wn as P,yn as S};
