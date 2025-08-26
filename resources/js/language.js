import { createI18n } from 'vue-i18n';
import { getCookie } from './helpers.js';

const locale = getCookie('pll_language') || 'ru';

const messages = {
	ru: {
	    close: 'Закрыть',
	    filter: 'Фильтры',
	    name: 'Имя',
	    phone: 'Телефон',
	    region: 'Выберите область',
	    city: 'Выберите город',
	    warehouse: 'Выберите отделение',
	    errName: "Имя должно содержать не менее 3 символов",
	    errPhone: 'Введите номер телефона',
	    errRegion: 'Выберите область',
	    errCity: 'Выберите город',
	    errWarehouse: 'Выберите отделение',
		errAddress: 'Введите адрес',
	    order: 'Замовити',
	    buy: 'Купить',
	    category: 'Категории',
		address: 'Адрес'
	},
	uk: {
	    close: 'Закрити',
	    filter: 'Фільтри',
	    name: "Ім'я",
	    phone: 'Телефон',
	    region: 'Оберіть область',
	    city: 'Оберіть місто',
	    warehouse: 'Оберіть відділення',
	    errName: "Ім'я повинно містити щонайменше 3 символи",
	    errPhone: 'Введіть номер телефону',
	    errRegion: 'Оберіть область',
	    errCity: 'Оберіть місто',
	    errWarehouse: 'Оберіть відділення',
		errAddress: 'Введіть адресу',
	    order: 'Заказать',
	    buy: 'Купити',
	    category: 'Категорії',
		address: 'Адреса'
	}
};

const i18n = createI18n({
	locale: locale,
	messages,
});

export default i18n;