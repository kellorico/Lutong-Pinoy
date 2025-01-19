<script setup>
import UpdateInfo from './partials/UpdateInfo.vue';
import UpdatePassword from './partials/UpdatePassword.vue';
import DeleteAccount from './partials/DeleteAccount.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
defineProps({
    user:Object,
    status: String
})
</script>

<template>
    <UserLayout>
        <Head title="- Profile"/>
    <UpdateInfo :user="user" :status="status"/>
    <UpdatePassword :status="status"/>
    <DeleteAccount/>
    </UserLayout>
</template>
