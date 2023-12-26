<script setup>
defineProps({
    src: String,
    alt: String,
    classes: {
        type: String,
        default: 'border-gray-700 hover:border-blue-500',
    },
});
</script>

<template>
    <img :src="src"
         class="w-44 h-auto rounded-md shadow-xl border-2 transition-all duration-500 ease-out cursor-pointer"
         :class="classes"
         :alt="alt"/>
</template>
