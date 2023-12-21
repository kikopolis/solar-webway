<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps(
    {
        classes: {
            type:    String,
            default: 'w-20 h-20',
        },
    });
</script>

<template>
    <ApplicationLogo :classes="classes"/>
</template>
