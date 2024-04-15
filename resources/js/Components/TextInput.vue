<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    required: false,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white text-black focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
