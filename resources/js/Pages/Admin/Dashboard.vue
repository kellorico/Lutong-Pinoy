<script setup>
import { computed, ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
const props = defineProps({
    users: Array,
    menus: Array,
    categories: Array,
    orders: Array,
});

// Update computed property to get latest 3 menus
const latestMenus = computed(() => {
    return props.menus
        ?.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 3);
});

// Add ref for selected menu and modal state
const selectedMenu = ref(null);
const isModalOpen = ref(false);

// Function to open modal with menu details
const openModal = (menu) => {
    selectedMenu.value = menu;
    isModalOpen.value = true;
};

// Function to close modal
const closeModal = () => {
    selectedMenu.value = null;
    isModalOpen.value = false;
};
</script>

<template>
    <AdminLayout>
        <div class="container mx-auto px-6 py-4">
            <h1 class="text-2xl font-bold mb-6 text-center">Admin Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Users Card -->
                <div class="bg-white border rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xl font-semibold">Users</div>
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                    <div class="text-gray-600 mb-4">
                        Total Users: {{ props.users?.length }}
                    </div>
                    <div class="mt-4">
                        <Link
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                            :href="route('admin.users')"
                            >View Users</Link
                        >
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white border rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xl font-semibold">Orders</div>
                        <i
                            class="fas fa-shopping-cart text-green-500 text-2xl"
                        ></i>
                    </div>
                    <div class="text-gray-600 mb-4">
                        Total Orders: {{ props.orders?.length }}
                    </div>
                    <div class="mt-4">
                        <Link
                            class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600"
                            :href="route('admin.orders')"
                            >View Orders</Link
                        >
                    </div>
                </div>

                <!-- Menus Card -->
                <div class="bg-white border rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xl font-semibold">Menus</div>
                        <i class="fas fa-utensils text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="text-gray-600 mb-4">
                        Total Menus: {{ props.menus?.length }}
                    </div>
                    <div class="mt-4">
                        <Link
                            class="w-full bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600"
                            :href="route('admin.menus')"
                            >View Menus</Link
                        >
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white border rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xl font-semibold">Categories</div>
                        <i class="fas fa-list text-red-500 text-2xl"></i>
                    </div>
                    <div class="text-gray-600 mb-4">
                        Total Categories: {{ props.categories?.length }}
                    </div>
                    <div class="mt-4">
                        <Link
                            class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
                            :href="route('admin.categories')"
                            >View Categories</Link
                        >
                    </div>
                </div>
            </div>

            <!-- Latest Menus Section -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Latest Menus Added</h2>
                <div
                    v-if="latestMenus?.length"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                >
                    <div
                        v-for="menu in latestMenus"
                        :key="menu.id"
                        class="bg-white border rounded-lg shadow-md p-4"
                    >
                        <div class="flex flex-col h-full">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    {{ menu.menu_name }}
                                </h3>
                                <p class="text-gray-600 mt-2">
                                    {{ menu.description }}
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Added:
                                    {{
                                        new Date(
                                            menu.created_at
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xl font-bold text-green-600">
                                    ₱{{ menu.price }}
                                </div>
                                <button
                                    @click="openModal(menu)"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="bg-white border rounded-lg shadow-md p-4 text-center text-gray-600"
                >
                    No menus available
                </div>
            </div>
        </div>
    </AdminLayout>

    <!-- Menu Details Modal -->
    <div
        v-if="isModalOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 relative">
            <!-- Close button -->
            <button
                @click="closeModal"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <!-- Modal content -->
            <div v-if="selectedMenu" class="mt-2">
                <h3 class="text-2xl font-bold mb-4">{{ selectedMenu.menu_name }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Menu Image -->
                    <div class="aspect-w-16 aspect-h-9">
                        <img
                            :src="
                                selectedMenu.image_path
                                    ? `/storage/${selectedMenu.image_path}`
                                    : 'https://via.placeholder.com/400x300?text=No+Image'
                            "
                            :alt="selectedMenu.name"
                            class="object-cover rounded-lg w-full h-full"
                        />
                    </div>

                    <!-- Menu Information -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold">Description</h4>
                            <p class="text-gray-600">
                                {{ selectedMenu.description }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold">Price</h4>
                            <p class="text-2xl font-bold text-green-600">
                                ₱{{ selectedMenu.price }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold">Category</h4>
                            <p class="text-gray-600">
                                {{
                                    selectedMenu.category?.category_name ||
                                    "Uncategorized"
                                }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold">Added On</h4>
                            <p class="text-gray-600">
                                {{
                                    new Date(
                                        selectedMenu.created_at
                                    ).toLocaleDateString()
                                }}
                            </p>
                        </div>

                        <!-- Additional details if available -->
                        <div v-if="selectedMenu.ingredients">
                            <h4 class="text-lg font-semibold">Ingredients</h4>
                            <p class="text-gray-600">
                                {{ selectedMenu.ingredients }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="mt-6 flex justify-end">
                    <button
                        @click="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
