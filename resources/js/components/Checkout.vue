<script setup>
import { ref, onMounted } from 'vue';
import Multiselect from "vue-multiselect";
import { useI18n } from 'vue-i18n';
import "vue-multiselect/dist/vue-multiselect.css";
import { getCookie } from '../helpers.js';

const rawLocale = getCookie('pll_language') || 'ru';
const locale = (rawLocale == 'uk') ? '' : 'Ru';
const {t} = useI18n();
const errors = ref([]);
const regions = ref([]);
const cities = ref([]);
const warehouses = ref([]);
const name = ref('');
const phone = ref('');
const address = ref('');
const selectedRegion = ref({});
const selectedCity = ref({});
const selectedWarehouse = ref({});
const preload = ref(false);

const props = defineProps({
    productId: {
        type: Number,
        required: 0
    }
});

function validateText(value) {
    if (!value) return "This field is required";
    if (value.length < 3) return "This field needs to be longer than 2 symbols";

    return true;
}

async function getRegions() {
	const response = await fetch('/wp-json/np/v1/regions');
	const result = await response.json();
	regions.value = result;
}

async function getCities(ref) {
	const response = await fetch(`/wp-json/np/v1/cities?region_ref=${ref}`);
	const result = await response.json();
	cities.value = result;
}

async function getWarehouses(ref) {
	const response = await fetch(`/wp-json/np/v1/warehouses?city_ref=${ref}`);
	const result = await response.json();
	warehouses.value = result;
}

async function submitOrder() {
	errors.value = [];

	if (!name.value || name.value.length < 3) {
		errors.value.push(t('errName'));
	}

	if (!phone.value || phone.value.length < 10) {
		errors.value.push(t('errPhone'));
	}

	if (!address.value || address.value.length < 3) {
		errors.value.push(t('errAddress'));
	}

	/*if (!selectedRegion.value?.Ref) {
		errors.value.push(t('errRegion'));
	}

	if (!selectedCity.value?.Ref) {
		errors.value.push(t('errCity'));
	}

	if (!selectedWarehouse.value?.Ref) {
		errors.value.push(t('errWarehouse'));
	}*/

	if (errors.value.length > 0) return;

	const payload = {
		name: name.value,
		phone: phone.value,
		address: address.value,
		/*region: selectedRegion.value.Description,
		city: selectedCity.value.Description,
		warehouse: selectedWarehouse.value.Description,*/
		productId: document.querySelector('.popup-order').dataset.productId
	};

	try {
		preload.value = true;

		const response = await fetch('/wp-json/myshop/v1/submit-order', {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify(payload)
		});

		const result = await response.json();

		if (response.ok && result.status == "success") {
			preload.value = false;
			location.href = result.redirect_url;
		}
	} catch (err) {
		console.error(err);
		alert('Помилка при оформленні замовлення');
	}
}

onMounted(() => {
	getRegions();
});
</script>

<template>
	<div v-if="preload" class="preload">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect fill="#317a2d" stroke="#317a2d" stroke-width="15" width="30" height="30" x="25" y="50"><animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate></rect><rect fill="#317a2d" stroke="#317a2d" stroke-width="15" width="30" height="30" x="85" y="50"><animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate></rect><rect fill="#317a2d" stroke="#317a2d" stroke-width="15" width="30" height="30" x="145" y="50"><animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate></rect></svg>
	</div>

	<div class="form-row">
		<label>{{ $t('name') }} <span class="required">*</span></label>
		<input v-model="name" class="input-text" type="text">
	</div>

	<div class="form-row">
		<label>{{ $t('phone') }} <span class="required">*</span></label>
		<input v-model="phone" class="input-text" type="text">
	</div>

	<div class="form-row">
		<label>{{ $t('address') }} <span class="required">*</span></label>
		<input v-model="address" class="input-text" type="text">
	</div>
	
	<!-- <div class="form-row" v-if="regions.length">
		<label>{{ $t('region') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedRegion"
			:options="regions"
			:label="`Description${locale}`"
			:placeholder="`${t('region')}`"
			@select="getCities(selectedRegion.Ref)"
		/>
	</div> -->

	<!-- <div class="form-row" v-if="cities.length">
		<label>{{ $t('city') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedCity"
			:options="cities"
			:label="`Description${locale}`"
			:placeholder="`${t('city')}`"
			@select="getWarehouses(selectedCity.Ref)"
		/>
	</div> -->

	<!-- <div class="form-row" v-if="warehouses.length">
		<label>{{ $t('warehouse') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedWarehouse"
			:options="warehouses"
			:label="`Description${locale}`"
			:placeholder="`${t('warehouse')}`"
		/>
	</div> -->

	<div class="text-center">
		<button @click="submitOrder" class="btn-green">{{ $t('order') }}</button>
	</div>

	<div class="errors-fields" v-if="errors.length">
		<div v-for="(error, index) in errors" :key="index">
			{{ error }}
		</div>
	</div>
</template>

<style>
	.text-center {margin-top: 20px;}

	.popup-order {
		min-width: 450px;
		max-width: 450px;

		.preload {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			background: radial-gradient(rgba(255,255,255,.8) 40%, transparent);

			svg {
				width: 50px;
				height: 50px;
			}
		}

		@media (max-width: 500px) {
			min-width: 320px;
			max-width: 320px;
		}
	}

	.form-row {
		label {
			margin-bottom: 5px;
			display: inline-block;
		}
		label span {color: red;}
		& + .form-row  {margin-top: 10px;}
		input {
			margin-top: 0;
			width: 100%;
		}
	}

	.multiselect__tags {border-color: var(--c-akcent);}
	.multiselect__element {
		span:after,
		span:hover:after {display: none;}
	}
	.multiselect__option:hover,
	.multiselect__option--highlight {background: #000;}

	.errors-fields {
		color: red;
		font-size: 12px;
		margin-top: 20px;
	}
</style>