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
        const url = window.location.pathname;
        const data = `first_name=${first_name}&last_name=${last_name}&phone=${phone}&email=${email}&date=${date}&time=${time}&url=${url}&action=send`;
        
        submitForm(e.target, data);
    });
}

function submitForm(form, data) {
    form.classList.add('preload');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            form.classList.remove('preload');
            form.reset();
            form.querySelector('.hidden').classList.remove('hidden');
        }
    }

    xhr.onerror = () => {
        console.log('Network error!');
    }
}