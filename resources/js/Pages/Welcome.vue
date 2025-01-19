<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
</script>

<template>
    <Head title="Welcome"/>
    <UserLayout>
        <div
            class="h-screen bg-cover bg-center shadow-lg mt-16"
            style="background-image: url('background.jpeg');"
        >
            <div class="flex items-center justify-center text-center h-full">
                <div class="text-white max-w-lg px-6">
                    <h1 class="text-4xl font-bold">
                        Savor the taste of home with
                        <span class="text-orange-500">Lutong Pinoy's</span>
                    </h1>
                    <p class="mt-4 text-lg">
                        Discover Authentic Filipino Flavors, Delivered Fresh to Your Doorstep! Prepared with love and the freshest ingredients.
                    </p>
                    <Link class="bg-orange-500 hover:bg-orange-600 rounded-md px-3 py-1">View Lutong Pino'y Foods</Link>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
