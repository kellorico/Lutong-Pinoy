<script setup>
import { computed } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';

// Define props to receive order data from the controller
const props = defineProps({
  orders: {
    type: Array,
    required: true
  }
});

// Function to calculate the total amount for each order dynamically
const calculateTotal = (order) => {
  return order.order_items?.reduce((total, item) => {
    if (item.menu) {
      return total + (Number(item.menu.price) * item.quantity);
    }
    return total; // If item.menu is null, just return the current total
  }, 0) || 0;
};

// Function to determine status color
const getStatusColor = (status) => {
  switch (status.toLowerCase()) {
    case 'completed':
      return 'text-green-500';
    case 'pending':
      return 'text-yellow-500';
    case 'cancelled':
      return 'text-red-500';
    case 'in progress':
      return 'text-indigo-500';
    default:
      return 'text-gray-500';
  }
};

// Sort orders to display the newest first
const sortedOrders = computed(() => {
  return [...props.orders].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});
</script>

<template>
    <UserLayout>
      <div class="container mx-auto my-10 mt-20">
        <h1 class="text-orange-500 text-3xl font-semibold mb-6">Order Details</h1>

        <div v-if="!sortedOrders.length" class="text-center py-8 text-gray-500 text-lg">
          No orders yet
        </div>

        <div v-for="order in sortedOrders" :key="order.id" class="bg-white p-6 rounded-lg shadow-md mb-4">
          <h2 class="text-xl font-medium">Order Status:
            <span :class="getStatusColor(order.status)">{{ order.status }}</span>
          </h2>
          <p class="text-gray-600">Order Date: {{ new Date(order.created_at).toLocaleDateString() }}</p>

          <div class="mt-4">
            <h3 class="text-lg font-medium">Products Ordered:</h3>
            <div class="grid gap-4 mt-2">
              <div v-for="item in order.order_items" :key="item.id"
                   class="border rounded-lg p-4 flex items-start space-x-4">
                <div class="w-24 h-24 flex-shrink-0">
                  <img v-if="item.menu?.image_path"
                       :src="item.menu.image_path ? `/storage/${item.menu.image_path}` : 'path/to/default-image.jpg'"
                       :alt="item.menu?.menu_name"
                       class="w-full h-full object-cover rounded-lg">
                  <div v-else class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-400">No image</span>
                  </div>
                </div>
                <div class="flex-grow">
                  <h4 class="text-lg font-medium">{{ item.menu?.menu_name || 'Unknown Item' }}</h4>
                  <div class="mt-2 space-y-1">
                    <div class="flex justify-between">
                      <span class="text-gray-600">Unit Price:</span>
                      <span class="font-medium">₱{{ item.menu ? Number(item.menu.price).toFixed(2) : '0.00' }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600">Quantity:</span>
                      <span class="font-medium">{{ item.quantity }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600">Subtotal:</span>
                      <span class="font-medium">₱{{ item.menu ? (Number(item.menu.price) * item.quantity).toFixed(2) : '0.00' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg">
            <span class="font-semibold text-lg">Total Amount:</span>
            <span class="text-xl font-bold text-indigo-600">₱{{ calculateTotal(order).toFixed(2) }}</span>
          </div>
        </div>
      </div>
    </UserLayout>
  </template>
