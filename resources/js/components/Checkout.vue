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
const selectedRegion = ref({});
const selectedCity = ref({});
const selectedWarehouse = ref({});

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

	if (!selectedRegion.value?.Ref) {
		errors.value.push(t('errRegion'));
	}

	if (!selectedCity.value?.Ref) {
		errors.value.push(t('errCity'));
	}

	if (!selectedWarehouse.value?.Ref) {
		errors.value.push(t('errWarehouse'));
	}

	if (errors.value.length > 0) return;

	const payload = {
		name: name.value,
		phone: phone.value,
		region: selectedRegion.value.Description,
		city: selectedCity.value.Description,
		warehouse: selectedWarehouse.value.Description,
		productId: document.querySelector('.popup-order').dataset.productId
	};

	try {
		const response = await fetch('/wp-json/myshop/v1/submit-order', {
			method: 'POST',
			headers: {'Content-Type': 'application/json'},
			body: JSON.stringify(payload)
		});

		const result = await response.json();

		if (response.ok && result.status == "success") {
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
	{{productId}}
	<div class="form-row">
		<label>{{ $t('name') }} <span class="required">*</span></label>
		<input v-model="name" class="input-text" type="text">
	</div>

	<div class="form-row">
		<label>{{ $t('phone') }} <span class="required">*</span></label>
		<input v-model="phone" class="input-text" type="text">
	</div>
	
	<div class="form-row" v-if="regions.length">
		<label>{{ $t('region') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedRegion"
			:options="regions"
			:label="`Description${locale}`"
			:placeholder="`${t('region')}`"
			@select="getCities(selectedRegion.Ref)"
		/>
	</div>

	<div class="form-row" v-if="cities.length">
		<label>{{ $t('city') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedCity"
			:options="cities"
			:label="`Description${locale}`"
			:placeholder="`${t('city')}`"
			@select="getWarehouses(selectedCity.Ref)"
		/>
	</div>

	<div class="form-row" v-if="warehouses.length">
		<label>{{ $t('warehouse') }} <span class="required">*</span></label>
		<Multiselect
			v-model="selectedWarehouse"
			:options="warehouses"
			:label="`Description${locale}`"
			:placeholder="`${t('warehouse')}`"
		/>
	</div>

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