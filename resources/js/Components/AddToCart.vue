<script setup>
import { ref } from "vue";

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
    if (cart.value.length === 0) return;

    // Example: Call API or handle order placement
    alert("Order placed successfully!");

    // Clear the cart
    cart.value = [];
}

// Function to remove an item from the cart
function removeFromCart(menuId) {
    cart.value = cart.value.filter(item => item.id !== menuId);
}
</script>

<template>
    <div>
        <!-- Cart Summary -->
        <div v-if="cart.length > 0" class="fixed bottom-4 right-4 p-6 bg-white shadow-lg rounded-lg border border-gray-200">
            <h2 class="text-lg font-bold mb-4">Cart</h2>

            <ul class="space-y-4">
                <li
                    v-for="item in cart"
                    :key="item.id"
                    class="flex items-center justify-between"
                >
                    <div>
                        <p class="text-gray-800 font-medium">{{ item.menu_name }}</p>
                        <p class="text-gray-600 text-sm">Quantity: {{ item.quantity }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <p class="text-gray-800 font-semibold">₱{{ item.price * item.quantity }}</p>
                        <button
                            @click="removeFromCart(item.id)"
                            class="text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </li>
            </ul>

            <button
                @click="placeOrder"
                class="mt-4 w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none"
            >
                Place Order
            </button>
        </div>
    </div>
</template>
