<script setup>
import { defineProps, onMounted, ref, computed } from 'vue';

const props = defineProps({
	product: {
		type: Object,
		default: {},
		required: true
	}
});

const price = computed(() => {
	const p = props.product.prices;

	const raw = Number(p.price) / Math.pow(10, p.currency_minor_unit); 

	const parts = raw
		.toFixed(p.currency_minor_unit)
		.split(".");

	let intPart = parts[0];
	const decPart = parts[1];

	intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, p.currency_thousand_separator);

	return `${intPart}${p.currency_decimal_separator}${decPart} ${p.currency_symbol}`;
});
</script>

<template>
	<a 
		:href="product.permalink" 
		class="image" 
		v-if="product.images[0] !== null"
	>	
		<img 
			v-if="product.images.length"
			:src="`${product.images[0].src}`" 
			loading="lazy" 
			alt=""
		>

		<img v-else :src="`/themes/master/img/no-image.png`" alt="">
	</a>

	<h2><a :href="product.permalink">{{ product.name }}</a></h2>

	<div class="price">
		<b>{{ price }}</b>
	</div>

	<a 
		:href="product.permalink" 
		class="btn-green btn-popup" 
		data-popup="order"
		:data-title="product.name"
		:data-productID="product.id"
	>
		<span>
			{{ $t('buy') }}
		</span>
	</a>
</template>