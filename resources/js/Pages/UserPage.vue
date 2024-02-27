<template>
    <Dashboard>
        <div>
            <div>
                <Head title="Add User" />
                <h1 class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center">
                    Add User
                </h1>
            </div>
            <form @submit.prevent="submit">
                <div>
                    <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</span>
                    <TextInput id="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />
                </div>
                <div class="mt-4">
                    <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</span>

                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="current-password" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create User
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Dashboard>
</template>
<script setup>
//Imports
import Dashboard from "@/Pages/Dashboard.vue";
import { Head, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

//Uses
const form = useForm({
    email: "",
    password: "",
    remember: false,
});

//Refs

//Props $ Emit

//Computed

//Mathods

//Hooks
</script>
<style scoped></style>
