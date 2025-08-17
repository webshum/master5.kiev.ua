<script setup>
import { defineProps, ref, onMounted, watch } from 'vue';
import { fetchProducts } from '../../main.js';
import Product from './Product.vue';
import { getCookie } from '../../helpers.js';

const locale = getCookie('pll_language') || 'ru';
const perPage = import.meta.env.VITE_API_PAR_PAGE || 9;
const products = ref([]);
const isLoading = ref(false);
const total = ref(1);
const totalPages = ref(1);

const props = defineProps({
	filters: {
		type: Object, 
		default: {},
		required: true
	},
	category: {
		type: Number, 
		default: 0
	}
});

const query = ref({
	per_page: perPage,
	page: 1,
	lang: locale,
	categoryID: props.category,
});

async function fetchProductsData(query) {
	isLoading.value = true;

	const result = await fetchProducts(query);

	products.value = result.data.products;
	total.value = result.data.total;
	totalPages.value = result.data.totalPages;
	isLoading.value = false;
}

function changePage(page) {
	if (page >= 1 && page <= totalPages.value) {
		query.value.page = page;

		let basePath = location.pathname.replace(/\/page\/\d+/, '');

		if (basePath.endsWith('/')) basePath = basePath.slice(0, -1);

		const newUrl = `${basePath}/page/${page}`;
		history.pushState({}, '', newUrl);

		fetchProductsData(query.value);
	}
}

onMounted(() => {
	fetchProductsData(query.value);
});

watch(() => props.filters, (newFilters) => {
    query.value = {
        ...query.value, 
        ...newFilters
    };
    fetchProductsData(query.value);
}, { deep: true });
</script>

<template>
	<div class="loading" v-if="isLoading"></div>

	<button class="btn-filters">
		{{ $t('filter') }}
	</button>

	<div>
		<div class="all-products">
			<div class="box-product" v-for="(product, index) in products" :key="index">
				<Product :product="product"/>
			</div>
		</div>
		
		<div class="pagination" v-if="totalPages >= 1">
			<ul>
				<li 
					v-for="(page, index) in totalPages" 
					:key="index"
					:class="{active: page == query.page}"
				>
					<a :href="`/page/${page}`" @click.prevent="changePage(page)">{{ page }}</a>
				</li>
			</ul>
		</div>
	</div>
</template>