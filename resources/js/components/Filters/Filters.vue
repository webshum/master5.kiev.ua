<script setup>
import { ref, onMounted, reactive } from 'vue';
import { fetchFilters, fetchFilterById } from '../../main.js';
import { getCookie } from '../../helpers.js';

const locale = getCookie('pll_language') || 'ru';
const filters = ref(null);
const emit = defineEmits(['filters']);
const perPage = import.meta.env.VITE_API_PAR_PAGE || 10;
const data = reactive({
	lang: locale,
	page: 1,
	per_page: perPage,
	orderby: "date",
	order: "desc",
	catalog_visibility: "catalog",
	attributes: []
});

const priceRange = reactive({
	min: null,
	max: null
});

async function fetchFiltersData() {
	const attrs = await fetchFilters(locale);

	const filtersWithTerms = await Promise.all(
		attrs.map(async attr => {
			const terms = await fetchFilterById(attr.id);

			return {
				...attr, 
				terms
			}
		})
	);

	filters.value = filtersWithTerms;
}

function onChoice(count, index, taxonomy, slug, checked) {
	if (!data.attributes) {
		data.attributes = [];
	}

	if (!data.attributes[count]) {
		data.attributes[count] = {
			attribute: taxonomy,
			operator: 'in',
			slug: []
		};
	}

	let attr = data.attributes[count];

	if (checked) {
		if (!attr.slug.includes(slug)) {
			attr.slug.push(slug);
		}
	} else {
		attr.slug = attr.slug.filter(s => s !== slug);

		if (attr.slug.length === 0) {
			data.attributes.splice(count, 1);
		}
	}

	emit('filters', data);
}

const onChange = () => {
	data.min_price = priceRange.min;
	data.max_price = priceRange.max;

  	emit('filters', { ...data });
};

onMounted(() => {
	fetchFiltersData();
});
</script>

<template>
	
	<form action="#" name="filters" class="filters">
		<input type="hidden" name="orderby" :value="data.orderby">
		<input type="hidden" name="order" :value="data.order">
		<input type="hidden" name="catalog_visibility" :value="data.catalog_visibility">
		<input type="hidden" name="per_page" :value="perPage">
		<input type="hidden" name="page" :value="page">
		<input type="hidden" name="lang" :value="data.lang">

		<div class="group group-price">
			<input type="number" v-model.number="priceRange.min" placeholder="0">
			<span>-</span> 
			<input type="number" v-model.number="priceRange.max" placeholder="99999">
			<button @click.prevent="onChange">ok</button>
		</div>

		<div 
			class="group" 
			v-for="(filter, count) in filters" 
			:key="count"
		>
			<div v-if="filter.label !== 'Артикул'">
				<div class="title">
					<h3>{{ filter.label }}</h3>
					<svg><use xlink:href="#arr"></use></svg>
				</div>

				<div class="inner">
					<label 
						class="label" 
						v-if="Array.isArray(filter.terms) && filter.terms.length"
						v-for="(term, index) in filter.terms"
						:key="index"
					>
						<input 
							type="checkbox" 
							@change="onChoice(count, index, filter.taxonomy, term.slug, $event.target.checked)"
						>
						<div class="checkbox"></div>
						<span>{{ term.name }}</span>
					</label>
				</div>
				
				<input type="hidden" :name="`attributes[${count}][attribute]`" :value="filter.taxonomy">
				<input type="hidden" :name="`attributes[${count}][operator]`" value="in">
			</div>
		</div>

		<div class="close-filters">
			{{ $t('close') }}
		</div>
	</form>
</template>