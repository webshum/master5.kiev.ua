document.querySelectorAll("[data-lazy-src]").forEach(e=>{e.src=e.dataset.lazySrc});document.querySelector(".btn-top").onclick=e=>{e.preventDefault(),window.scrollTo({top:0,behavior:"smooth"})};document.querySelector(".btn-social").addEventListener("click",e=>{document.querySelector(".btn-social").classList.add("active")});document.querySelector(".btn-nav").addEventListener("click",e=>{e.preventDefault(),document.body.classList.toggle("nav-open")});function u(){let e=document.querySelectorAll(".btn-popup"),c=document.querySelector(".popup-overlay"),t=null,a=null;for(var l=0;l<e.length;l++)e[l].addEventListener("click",function(o){o.preventDefault(),t=document.querySelector(".popup-"+this.getAttribute("data-popup")),a=t.querySelector(".popup-close"),console.log(t);let r=window.pageYOffset||document.documentElement.scrollTop;window.pageXOffset||document.documentElement.scrollLeft,c.classList.add("active"),t.classList.add("active"),t.style.top=r+100+"px",a.addEventListener("click",n),c.addEventListener("click",n)});document.addEventListener("keydown",function(o){o.keyCode==27&&n(o)});function n(o){o.preventDefault(),c.classList.remove("active"),t.classList.remove("active")}}u();
