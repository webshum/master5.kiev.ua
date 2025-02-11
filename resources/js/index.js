import '../css/index.css';

document.querySelectorAll('[data-lazy-src]').forEach(img => {
    img.src = img.dataset.lazySrc;
});

document.querySelector('.btn-top').onclick = e => {
    e.preventDefault();

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
}

document.querySelector('.btn-social').addEventListener('click', e => {
    if (e.target.closest('.btn-social').classList.contains('active')) {
        document.querySelector('.btn-social').classList.remove('active');
    } else {
        document.querySelector('.btn-social').classList.add('active');
    }
});

document.querySelector('.btn-nav').addEventListener('click', e => {
   e.preventDefault();
    document.body.classList.toggle('nav-open');
});

/* HEADER 
---------------------------------------------------- */
function header() {
    const header = document.getElementById('header');
    const bar = header.querySelector('.head-bar');
    const home = document.querySelector('.wrapper');
    
    window.addEventListener('scroll', e => {
        if (window.scrollY >= bar.clientHeight) {
            document.body.classList.add('fixd-header');
            const headMain = document.querySelector('.head-main');

            home.style.marginTop = `${headMain.clientHeight}px`;
        } else {
            document.body.classList.remove('fixd-header');
            home.removeAttribute('style');
        }
    });
}

header();  

/* POPUP 
---------------------------------------------------- */
function popup() {
    let btn = document.querySelectorAll('.btn-popup');
    let overlay = document.querySelector('.popup-overlay');
    let popup = null;
    let close = null;
    let _this = this;

    for (var i = 0; i < btn.length; i++) {
        btn[i].addEventListener('click', function(e) {
            e.preventDefault();

            popup = document.querySelector('.popup-' + this.getAttribute('data-popup'));
            close = popup.querySelector('.popup-close');

            let top  = window.pageYOffset || document.documentElement.scrollTop,
                left = window.pageXOffset || document.documentElement.scrollLeft;

            overlay.classList.add('active');
            popup.classList.add('active');
            popup.style.top = (top + 100) + 'px';

            close.addEventListener('click', closePopup);
            overlay.addEventListener('click', closePopup);
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.keyCode == 27) closePopup(e);
    });

    function closePopup(e) {
        e.preventDefault();
        overlay.classList.remove('active');
        popup.classList.remove('active');
    }
}

popup();

/* SUBMIT
---------------------------------------------------- */ 
if (document.forms.order != null) {
    document.forms.order.addEventListener('submit', e => {
        e.preventDefault();

        const first_name = e.target.first_name ? e.target.first_name.value : '';
        const last_name = e.target.last_name ? e.target.last_name.value : '';
        const phone = e.target.phone ? e.target.phone.value : '';
        const email = e.target.email ? e.target.email.value : '';
        const date = e.target.date ? e.target.date.value : '';
        const time = e.target.time ? e.target.time.value : '';
        const title = e.target.title ? e.target.title.value : '';
        const link = window.location.href;
        const slug = window.location.pathname;
        const data = `first_name=${first_name}&last_name=${last_name}&phone=${phone}&email=${email}&date=${date}&time=${time}&title=${title}&slug=${slug}&link=${link}&action=send`;
        
        submitForm(e.target, data);
    });
}

/* ACCORDEON
------------------------------------ */
function accordeon() {
    let accordeon = document.querySelectorAll('.accordeon');
    let flag = true;

    if (accordeon != null) {
        for (let i = 0; i < accordeon.length; i++) {
            const item = accordeon[i].querySelectorAll('.item-accordeon');

            for (let j = 0; j < item.length; j++) {
                let btn = item[j].querySelector('.btn-accordeon');
                
                btn.addEventListener('click', openAccordeon);

                if (item[j].classList.contains('active')) {
                    let content = item[j].querySelector('.content-accordeon');
                    let inner = item[j].querySelector('.inner-accordeon');
                    content.style.height = (inner.clientHeight + 2) + 'px';
                }
            }
        }
    }

    function openAccordeon(e) {
        let item = this.closest('.accordeon').querySelectorAll('.item-accordeon');
        let inner = this.parentNode.querySelector('.inner-accordeon');
        let content = this.parentNode.querySelector('.content-accordeon');  

        if (this.parentNode.classList.contains('active')) {            
            this.parentNode.classList.remove('active');
            content.removeAttribute('style');
        } else {    
            for (let i = 0; i < item.length; i++) {
                item[i].classList.remove('active');
                item[i].querySelector('.content-accordeon').removeAttribute('style');
            }

            this.parentNode.classList.add('active');
            content.style.height = (inner.clientHeight + 2) + 'px';
        }    
    }
}

accordeon();

function submitForm(form, data) {
    form.classList.add('preload');
    const submitName = form.querySelector('input[type="submit"]').value; 

    form.querySelector('input[type="submit"]').value = '...';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            form.classList.remove('preload');
            form.reset();
            form.querySelector('.hidden').classList.remove('hidden');
            form.querySelector('input[type="submit"]').value = submitName;
        }
    }

    xhr.onerror = () => {
        console.log('Network error!');
    }
}