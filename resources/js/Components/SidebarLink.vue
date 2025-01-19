<script setup>
defineProps({
    routeName: String,
    componentName: String
})
</script>

<template>
    <Link :href="route(routeName)" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
    :class="{'bg-orange-100 text-orange-500' : $page.component === componentName}">
        <slot/>
    </Link>
</template>
