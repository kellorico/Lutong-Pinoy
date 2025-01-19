<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import { ref, computed } from "vue";
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// Define props
const props = defineProps({
    menus: Array,
    categories: Array,
    user_id: Number
});

// Cart state
const cart = ref([]);

// Function to add items to the cart
function addToCart(menu) {
    const existingItem = cart.value.find(item => item.id === menu.id);

    if (existingItem) {
        // If item already exists in the cart, increment the quantity
        existingItem.quantity++;
    } else {
        // Otherwise, add the item with quantity 1
        cart.value.push({ ...menu, quantity: 1 });
    }
}

// Function to place an order
function placeOrder() {
    if (cart.value.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Empty Cart',
            text: 'Please add items to your cart before placing an order.'
        });
        return;
    }

    const orderData = {
        user_id: props.user_id,  // Use the authenticated user's ID
        status: 'pending',
        total_amount: calculateTotal(),
        order_items: cart.value.map(item => ({
            menu_id: item.id,
            quantity: item.quantity,
            price: item.price
        }))
    };

    router.post('/orders', orderData, {
        onSuccess: () => {
            cart.value = [];
            Swal.fire({
                icon: 'success',
                title: 'Order Placed!',
                text: 'Your order has been successfully placed.'
            });
        },
        onError: (errors) => {
            Swal.fire({
                icon: 'error',
                title: 'Order Failed',
                text: 'There was an error placing your order. Please try again.'
            });
        }
    });
}

// Function to remove an item from the cart
function removeFromCart(menu) {
    cart.value = cart.value.filter(item => item.id !== menu);
}

// Function to decrease the quantity of an item
function decreaseQuantity(menu) {
    const item = cart.value.find(item => item.id === menu);
    if (item && item.quantity > 1) {
        item.quantity--;
    }
}

// Calculate total price of all items in the cart
function calculateTotal() {
    return cart.value.reduce((total, item) => total + item.price * item.quantity, 0);
}

// Group menus by category
const groupedMenus = computed(() => {
    return props.menus.reduce((acc, menu) => {
        const categoryName = menu.category?.category_name || "Uncategorized";
        if (!acc[categoryName]) {
            acc[categoryName] = [];
        }
        acc[categoryName].push(menu);
        return acc;
    }, {});
});

// Check if there are any menus
const hasMenus = computed(() => {
    return props.menus.length > 0;
});
</script>

<template>
    <UserLayout>
        <div class="mt-20 p-6 space-y-8">
            <h1 class="text-2xl font-bold text-orange-400">Menus</h1>

            <!-- Show message if there are no menus -->
            <div v-if="!hasMenus" class="text-center text-gray-500">
                <p>No menus yet</p>
            </div>

            <!-- Render menus grouped by category -->
            <div v-for="(categoryMenus, category) in groupedMenus" :key="category" class="space-y-4">
                <h2 class="text-xl font-bold text-gray-700 flex items-center">
                    <i class="fa fa-utensils text-orange-400 mr-2"></i> <!-- Replace with your preferred icon -->
                    {{ category }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="menu in categoryMenus" :key="menu.id"
                        class="border border-gray-200 rounded-lg shadow-md p-4 bg-white">
                        <img :src="menu.image_path ? `/storage/${menu.image_path}` : 'path/to/default-image.jpg'"
                            :alt="menu.menu_name" class="w-full h-40 object-cover rounded-md mb-4" />
                        <h3 class="text-lg font-bold text-gray-800">{{ menu.menu_name }}</h3>
                        <p class="text-gray-600 text-sm mt-2">{{ menu.description }}</p>
                        <p class="text-gray-800 font-semibold mt-4">Price: ₱{{ menu.price }}</p>

                        <!-- Add to Cart Button -->
                        <button @click="addToCart(menu)"
                            class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <!-- Cart Summary -->
            <div v-if="cart.length > 0"
                class="fixed bottom-4 right-4 p-6 bg-white shadow-lg rounded-lg border border-gray-200">
                <h2 class="text-lg font-bold mb-4">Cart</h2>

                <ul class="space-y-4">
                    <li v-for="item in cart" :key="item.id" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-800 font-medium">{{ item.menu_name }}</p>
                            <p class="text-gray-600 text-sm">Quantity: {{ item.quantity }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <p class="text-gray-800 font-semibold">₱{{ item.price * item.quantity }}</p>

                            <!-- Minus Button to Decrease Quantity -->
                            <button @click="decreaseQuantity(item.id)" class="text-gray-500 hover:text-gray-700"
                                :disabled="item.quantity <= 1">
                                -
                            </button>

                            <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700">
                                Remove
                            </button>
                        </div>
                    </li>
                </ul>

                <div class="mt-4">
                    <p class="text-lg font-semibold">Total: ₱{{ calculateTotal().toFixed(2) }}</p>
                </div>

                <button @click="placeOrder"
                    class="mt-4 w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none">
                    Place Order
                </button>
            </div>
        </div>
    </UserLayout>
</template>
