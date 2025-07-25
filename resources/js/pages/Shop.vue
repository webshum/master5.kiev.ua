<script setup>
import { ref } from 'vue';
import Products from '../components/Products/Products.vue';
import Filters from '../components/Filters/Filters.vue';

const filters = ref([]);
</script>

<template>
	<Filters @filters="filters = $event"/>
	<Products :filters="filters"/>
</template>