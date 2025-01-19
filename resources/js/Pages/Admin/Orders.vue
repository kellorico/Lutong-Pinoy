<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import Swal from "sweetalert2";
import { router } from '@inertiajs/vue3';

const props = defineProps({
    orders: {
        type: Array,
        default: () => []
    }
});

const showEditModal = ref(false);
const editingOrder = ref(null);

const calculateTotalAmount = (items) =>
    items.reduce((total, item) => total + (item.price * item.quantity), 0);

const editOrder = (order) => {
    editingOrder.value = {
        id: order.id,
        customer_name: order.user?.username || '',
        order_items: order.order_items.map(item => ({
            id: item.id,
            menu_id: item.menu?.id,
            menu_name: item.menu?.menu_name,
            quantity: item.quantity,
            price: item.price,
        })),
        status: order.status,
        total_amount: calculateTotalAmount(order.order_items),
    };
    showEditModal.value = true;
};

const updateOrder = () => {
    const updateData = {
        status: editingOrder.value.status,
        total_amount: editingOrder.value.total_amount,
        order_items: editingOrder.value.order_items.map(item => ({
            id: item.id || null,
            quantity: item.quantity,
            price: item.price,
        })),
    };

    router.put(`/admin/orders/${editingOrder.value.id}`, updateData, {
        onSuccess: () => {
            showEditModal.value = false;
            Swal.fire({
                title: "Success!",
                text: "Order updated successfully",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        },
        onError: (errors) => {
            Swal.fire({
                title: "Error!",
                text: Object.values(errors).join('\n') || "Failed to update order",
                icon: "error",
                timer: 3000,
            });
        },
    });
};

const deleteOrder = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/orders/${id}`, {
                onSuccess: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Order has been deleted",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                },
                onError: () => {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete order",
                        icon: "error",
                    });
                },
            });
        }
    });
};
</script>


    <template>
        <AdminLayout>
            <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <i class="fa-solid fa-box"></i> Orders
                </li>
            </ol>
        </nav>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <div v-if="orders.length === 0" class="text-center p-4">
                <p class="text-gray-600">No Orders found.</p>
            </div>
            <table v-else class="w-full text-sm text-left text-black bg-white">
                <thead class="text-xs text-black uppercase bg-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-center">Order Date</th>
                        <th class="px-6 py-3 text-center">#</th>
                        <th class="px-6 py-3 text-center">Order ID</th>
                        <th class="px-6 py-3 text-center">Customer Name</th>
                        <th class="px-6 py-3 text-center">Items</th>
                        <th class="px-6 py-3 text-center">Total Amount</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order, index) in orders" :key="order.id" class="bg-white border-b">
                        <td class="px-6 py-4 text-center">
                            {{ new Date(order.created_at).toLocaleString() }}
                        </td>
                        <th class="text-center">{{ index + 1 }}</th>
                        <td class="px-6 py-4 text-center">{{ order.id }}</td>
                        <td class="px-6 py-4 text-center">{{ order.user?.username }}</td>
                        <td class="px-6 py-4 text-center">
                            <div v-for="item in order.order_items" :key="item.id">
                                {{ item.menu?.menu_name || 'Unknown Item' }} (x{{ item.quantity }}) - &#8369; {{ item.price }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">  &#8369; {{ order.total_amount }}</td>
                        <td class="px-6 py-4 text-center">
                            <span :class="{
                                'text-green-500': order.status === 'completed',
                                'text-yellow-500': order.status === 'pending',
                                'text-red-500': order.status === 'cancelled',
                                'text-indigo-500': order.status === 'in_progress',
                            }">
                                {{ order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                class="bg-blue-500 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-600 mx-1"
                                @click="editOrder(order)"
                            >
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button
                                class="bg-red-500 text-white px-4 py-2 text-sm rounded-md hover:bg-red-600 mx-1"
                                @click="deleteOrder(order.id)"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


            <!-- Edit Order Modal -->
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-2 rounded-md shadow-md w-1/2">
                    <h2 class="text-lg font-semibold mb-4">Edit Order</h2>
                    <form @submit.prevent="updateOrder">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 text-gray-700">Customer Name</label>
                            <input type="text" v-model="editingOrder.customer_name" class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 text-gray-700">Items</label>
                            <div v-for="(item, index) in editingOrder.order_items" :key="index" class="flex gap-2 mb-2">
                                <div class="flex-grow">
                                    <span class="block text-sm text-gray-600">{{ item.menu_name }}</span>
                                </div>
                                <div class="w-24">
                                    <input
                                        type="number"
                                        v-model="item.quantity"
                                        @input="editingOrder.total_amount = calculateTotalAmount(editingOrder.order_items)"
                                        class="w-full p-2 border rounded"
                                        min="1"
                                    >
                                </div>
                                <div class="w-24">
                                    <span class="block p-2">${{ item.price * item.quantity }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 text-gray-700">Total Amount &#8369;</label>
                            <input
                                type="number"
                                v-model="editingOrder.total_amount"
                                class="w-full p-2 border rounded"
                                readonly
                            >
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2 text-gray-700">Status</label>
                            <select v-model="editingOrder.status" class="w-full p-2 border rounded">
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-[#306679] hover:bg-[#255065] text-white rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    </template>
