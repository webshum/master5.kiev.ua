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
    e.preventDefault();
    document.querySelector('.btn-social').classList.toggle('active');
});

document.querySelector('.btn-nav').addEventListener('click', e => {
   e.preventDefault();
    document.body.classList.toggle('nav-open');
});

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
            console.log(popup);
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
