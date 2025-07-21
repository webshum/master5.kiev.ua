<script setup>
import { ref, onMounted } from 'vue';
import { fetchFilters, fetchFilterById } from '../../main.js';
import { getCookie } from '../../helpers.js';

const locale = getCookie('pll_language') || 'ru';
const filters = ref(null);
const emit = defineEmits(['filters']);
const perPage = import.meta.env.VITE_API_PAR_PAGE || 10;

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

const onChange = async (e) => {
	const dataForm = new FormData(document.forms.filters);
	const data = Object.fromEntries(dataForm.entries());

	if (data.min_price) {
		data.min_price = Math.round(parseFloat(data.min_price) * 100);
	}
	
	if (data.max_price) {
		data.max_price = Math.round(parseFloat(data.max_price) * 100);
	}

	emit('filters', data);
};

onMounted(() => {
	fetchFiltersData();
});
</script>

<template>
	<form action="#" name="filters" class="filters">
		<input type="hidden" name="orderby" value="date">
		<input type="hidden" name="order" value="desc">
		<input type="hidden" name="catalog_visibility" value="catalog">
		<input type="hidden" name="per_page" :value="perPage">
		<input type="hidden" name="page" value="1">

		<div class="group group-price">
			<input type="number" name="min_price" placeholder="0">
			<span>-</span> 
			<input type="number" name="max_price" placeholder="99999">
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
							:name="`attributes[${count}][slug][${index}]`" 
							:value="term.slug"
							@change="onChange"
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