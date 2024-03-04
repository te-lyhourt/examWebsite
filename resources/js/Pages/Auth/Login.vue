<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <Head title="Log in" />
            <h1 class="mb-4 mt-4 font-medium text-5xl text-gray-700 dark:text-gray-300 grid place-content-center">
                Login
            </h1>

            <form @submit.prevent="submit">
                <div>
                    <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</span>

                    <TextInput id="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</span>

                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="current-password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm ,usePage} from '@inertiajs/vue3';
import { onMounted } from 'vue';

onMounted(() => {
    const page = usePage();
    // Redirect to dashboard if user is logged in
    if (page.props.auth.user) {
        route('page.group')
    }
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
//submit form function
const submit = () => {
    form.post(route('user.login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
