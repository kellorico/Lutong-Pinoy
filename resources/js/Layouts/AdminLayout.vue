<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const { url } = usePage(); // Get the current URL from Inertia's usePage helper
const page = usePage();
const searchQuery = ref("");
const showDropdown = ref(false);

// Access the logged-in user data from Inertia props
const user = page.props.auth.user;

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};
</script>

<template>
    <!-- Header -->
    <header class="flex items-center p-4 bg-[#F5F5DC] text-white relative">
        <!-- Left Side: Profile Dropdown (remains left aligned) -->

        <!-- Centered Search Bar -->
        <div class="flex-1 flex justify-center">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search..."
                class="px-4 py-2 w-1/3 bg-white text-black rounded-md border-none focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-500"
            />
        </div>

        <div class="mr-auto relative">
            <button
                @click="toggleDropdown"
                class="flex items-center gap-2 px-4 py-2 text-black bg-white rounded-md hover:bg-gray-600 hover:text-white focus:outline-none"
            >
                <!-- Display the logged-in user's username -->
                {{ user?.username }}
            </button>
            <div
                v-if="showDropdown"
                class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-50"
            >
                <Link href="/admin/profile" class="block px-4 py-2 hover:bg-gray-100">
                    Profile Settings
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                >
                    Logout
                </Link>
            </div>
        </div>
        <!-- Right Side (empty space, can be used for other content) -->
        <div class="ml-auto"></div>
    </header>

    <!-- Sidebar -->
    <div class="flex">
        <aside
            class="fixed top-0 left-0 w-64 bg-[#306679] text-white min-h-screen p-4"
        >
            <h1 class="text-xl font-bold mb-4">Lutong Pinoy</h1>
            <nav class="space-y-2">
                <Link
                    :href="route('admin.dashboard')"
                    :class="{
            'flex items-center px-4 py-2 rounded-md hover:bg-gray-700': true,
            'bg-gray-700': url === '/admin/dashboard',
          }"
                >
                    <i class="fa-solid fa-house mr-2"></i>
                    Home
                </Link>
                <Link
                    href="/admin/categories"
                    :class="{
            'flex items-center px-4 py-2 rounded-md hover:bg-gray-700': true,
            'bg-gray-700': url === '/admin/categories',
          }"
                >
                    <i class="fa-solid fa-list mr-2"></i>
                    Categories
                </Link>
                <Link
                    href="/admin/menus"
                    :class="{
            'flex items-center px-4 py-2 rounded-md hover:bg-gray-700': true,
            'bg-gray-700': url === '/admin/menus',
          }"
                >
                    <i class="fa-solid fa-utensils mr-2"></i>
                    Menu
                </Link>
                <Link
                    :href="route('admin.orders')"
                    :class="{
            'flex items-center px-4 py-2 rounded-md hover:bg-gray-700': true,
            'bg-gray-700': url === '/admin/orders',
          }"
                >
                    <i class="fa-solid fa-receipt mr-2"></i>
                    Orders
                </Link>
                <Link
                    :href="route('admin.users')"
                    :class="{
            'flex items-center px-4 py-2 rounded-md hover:bg-gray-700': true,
            'bg-gray-700': url === '/admin/users',
          }"
                >
                    <i class="fa-solid fa-users mr-2"></i>
                    Users
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 ml-64">
            <slot />
        </main>
    </div>
</template>
