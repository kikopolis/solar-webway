<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
            <ApplicationLogo class="block h-12 w-auto" />

            <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
                Welcome to Solar Webways Customer Portal!
            </h1>

            <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
                We would love to hear from you! Please send us a cosmic fax as soon as you can!
            </p>
        </div>

        <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <p class="text-xs">
                Disclaimer: This is a demo site. Please do not send us a cosmic fax. Nor a regular fax. We don't have a fax machine.
            </p>
        </div>
    </div>
</template>
