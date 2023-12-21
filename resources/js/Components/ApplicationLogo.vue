<script setup>
import logo from '../../images/app-logo.png';

defineProps(
    {
        classes: {
            type:    String,
            default: 'w-20 h-20',
        },
    });
</script>

<template>
    <img :src="logo" class="block border-solid border-2 border-black rounded shadow-xl" :class="classes" alt="Logo">
</template>
