<template>
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <img src="/path-to-logo.png" alt="Logo" class="h-8 w-8 mr-2" />
            <div>
                <h1 class="text-xl font-bold">Lutong Pinoy</h1>
                <p class="text-sm text-gray-500">Authentic Filipino Flavors</p>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="flex-1 mx-4">
            <input
                type="text"
                placeholder="Search for food"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-700"
            />
        </div>
        <!-- Login Button -->
        <button class="bg-orange-500 text-white px-4 py-2 rounded-lg font-medium">
            LOG IN
        </button>
    </header>
</template>

<script setup>
</script>

<style scoped>
/* Additional styles if needed */
</style>
