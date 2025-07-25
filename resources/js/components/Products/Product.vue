<script setup>
import { defineProps, onMounted, ref, computed } from 'vue';

const props = defineProps({
	product: {
		type: Object,
		default: {},
		required: true
	}
});
</script>

<template>
	<a 
		:href="product.permalink" 
		class="image" 
	>	
		<img 
			v-if="product.image"
			:src="`${product.image}`" 
			loading="lazy" 
			alt=""
		>

		<img v-else :src="`/themes/master/img/no-image.png`" alt="">
	</a>

	<h2><a :href="product.permalink">{{ product.name }}</a></h2>

	<div class="price" v-html="product.price"></div>

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