<script setup>
import { Head, Link } from '@inertiajs/vue3';
import BookingSystem from '@/Components/BookingSystem.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TopNav from '@/Components/TopNav.vue';
</script>

<template>
    <Head title="Book a trip to Your wonderland!"/>

    <template v-if="$page.props.auth.user">
        <AppLayout>
            <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-green-700 selection:text-white">
                <BookingSystem/>
            </div>
        </AppLayout>
    </template>

    <template v-else>
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-green-700 selection:text-white">
            <div class="sm:fixed sm:top-0 sm:end-0 p-6 text-end z-10">
                <TopNav/>
            </div>
            <BookingSystem/>
        </div>
    </template>
</template>
