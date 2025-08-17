const URL = import.meta.env.VITE_API_URL;
const API_URL_GET_PRODUCTS = URL + '/wp-json/custom/v1/products';
const API_URL_GET_FILTERS = URL + '/wp-json/custom/v1/attribute-terms';
const API_URL_GET_CATEGORIES = URL + '/wp-json/custom/v1/categories';

const fetchProducts = async (data) => {
	try {
		const response = await fetch(API_URL_GET_PRODUCTS, {
		    method: 'POST',
		    headers: {
		        'Content-Type': 'application/json',
		    },
		    body: JSON.stringify(data),
	    });

		if (response.ok) {
			const json = await response.json();
			const total = Number(response.headers.get('X-WP-Total'));
			const totalPages = Number(response.headers.get('X-WP-TotalPages'));

			return {
				data: json,
				meta: {
					total,
					totalPages
				}
			};
		}
	} catch (error) {
		console.log('Fetch error get_products: ', error);
	}
}

const fetchCategories = async (local) => {
	try {
		const response = await fetch(`${API_URL_GET_CATEGORIES}?lang=${local}`);

		if (response.ok) {
			const json = await response.json();
			return json;
		} 
	} catch (error) {
		console.log('Fetch error get_categories: ', error);
	}
}

const fetchFilters = async (local) => {
	try {
		const response = await fetch(`${API_URL_GET_FILTERS}?lang=${local}`);

		if (response.ok) {
			const json = await response.json();
			return json;
		}
	} catch (error) {
		console.error('Fetch error get_filters: ', error);
	}
}

const fetchFilterById = async (id) => {
	try {
		const url = `${URL}/wp-json/wc/store/v1/products/attributes/${id}/terms`;
		const response = await fetch(url);

		if (response.ok) {
			const json = await response.json();
			return json;
		}
	} catch (error) {
		console.log('Fetch error get_filter_by_id: ', error);
	}
}

export {
	fetchProducts,
	fetchCategories,
	fetchFilters,
	fetchFilterById
};