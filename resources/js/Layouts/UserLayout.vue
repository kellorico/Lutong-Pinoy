<script setup>
import SidebarLink from "@/Components/SidebarLink.vue";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();
const user = page.props.auth.user; // Access the authenticated user
const show = ref(false);

// Toggle dropdown
const toggleDropdown = () => {
    show.value = !show.value;
};


</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-md flex items-center justify-between px-6 py-4 fixed w-full">
            <div class="flex items-center">
                <!-- Logo -->
                <Link :href="route('welcome')" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Lutong Pinoy</h1>
                        <p class="text-sm text-gray-500">Authentic Filipino Flavors</p>
                    </div>
                </Link>
            </div>

            <!-- Search bar -->
            <div v-if="user" class="flex-grow mx-4">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search for food"
                        class="w-full border border-gray-300 rounded-full px-4 py-2 text-gray-800 focus:outline-none focus:ring focus:ring-orange-200"
                    />
                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500">🔍</span>
                </div>
            </div>

            <!-- User Dropdown or Login Button -->
            <div>
                <template v-if="user">
                    <div id="userDropdown" class="relative">
                        <div
                            @click="toggleDropdown"
                            class="flex items-center space-x-2 px-4 py-2 bg-orange-500 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-orange-300 cursor-pointer"
                            :class="{'bg-orange-600' : show}"
                        >
                            <span>{{ user.username }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div
                            v-show="show"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg"
                        >
                            <Link
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                :href="route('profile.edit')"
                            >
                                Profile
                            </Link>
                            <Link
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                :href="route('order.details')"
                            >
                                Order Details
                            </Link>
                            <Link :href="route('logout')"
                            method="post"
                            as="button"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Logout
                            </Link>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-4 py-2 text-white bg-orange-500 rounded-full hover:bg-orange-600 focus:outline-none"
                    >
                        Log In
                    </Link>
                </template>
            </div>
        </header>

        <!-- Content -->
        <div class="flex flex-grow">
            <!-- Sidebar -->
            <!-- <aside v-if="user" class="w-48 bg-white border-r border-gray-200 shadow-md">
                <nav v-if="user.role === 'customer'" class="flex flex-col py-4 space-y-2">
                    <SidebarLink routeName="dashboard" componentName="Foods">
                        <i class="fas fa-utensils w-6 h-6 text-orange-500"></i>
                        <span class="ml-2 font-medium">Foods</span>
                    </SidebarLink>

                    <SidebarLink routeName="drinks" componentName="Drinks">
                        <i class="fas fa-coffee w-6 h-6 text-orange-500"></i>
                        <span class="ml-2 font-medium">Drinks</span>
                    </SidebarLink>

                    <SidebarLink routeName="snacks" componentName="Snacks">
                        <i class="fas fa-cookie-bite w-6 h-6 text-orange-500"></i>
                        <span class="ml-2 font-medium">Snacks</span>
                    </SidebarLink>

                    <SidebarLink routeName="desserts" componentName="Desserts">
                        <i class="fas fa-ice-cream w-6 h-6 text-orange-500"></i>
                        <span class="ml-2 font-medium">Desserts</span>
                    </SidebarLink>
                </nav>
                <nav v-if="user.role === 'admin'" class="flex flex-col py-4 space-y-2">
                    <SidebarLink routeName="drinks" componentName="Drinks">
                        <span class="ml-2 font-medium">Dashboard</span>
                    </SidebarLink>
                    <SidebarLink routeName="admin.categories" componentName="Admin/Categories">
                        <span class="ml-2 font-medium">Categories</span>
                    </SidebarLink>
                    <SidebarLink routeName="admin.menus" componentName="Admin/Menus">
                        <span class="ml-2 font-medium">Menus</span>
                    </SidebarLink>
                    <SidebarLink routeName="admin.menus" componentName="Admin/Orders">
                        <span class="ml-2 font-medium">Orders</span>
                    </SidebarLink>
                    <SidebarLink routeName="admin.users" componentName="Admin/Users">
                        <span class="ml-2 font-medium">Users</span>
                    </SidebarLink>
                </nav>
            </aside> -->

            <!-- Main Section -->
            <main class="flex-grow p-6 bg-gray-50">
                <!-- Hero Section -->


                <!-- Dynamic Content Section -->
                <div>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
