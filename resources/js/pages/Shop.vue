<script setup>
import { ref, defineProps } from 'vue';
import Products from '../components/Products/Products.vue';
import Filters from '../components/Filters/Filters.vue';

const filters = ref([]);

const props = defineProps({
	category: {
		type: Number, 
		default: 0
	}
});
</script>

<template>
	<Filters @filters="filters = $event"/>
	<Products :filters="filters" :category="category"/>
</template>