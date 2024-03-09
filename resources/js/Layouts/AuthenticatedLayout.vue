<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div
                class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"
            >
                <!-- top bar -->
                <div
                    class="flex justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                >
                    <header
                        class="bg-white dark:bg-gray-800"
                        v-if="$slots.header"
                    >
                        <div
                            class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"
                        >
                            <slot name="header" />
                        </div>
                    </header>
                    <div class="flex items-center ms-6 justify-end">
                        <div
                            class="font-medium text-sm text-gray-500 mr-2 inline-block"
                        >
                            {{ $page.props.auth.user.email }}
                        </div>
                        <NavDropdown class="option" text="GO TO" :showUser="showUser"> </NavDropdown>

                        <Link
                            :href="route('logout')"
                            class="h-btn px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            method="post"
                            as="button"
                        >
                            LOG OUT
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex justify-content-stretch">
                <!-- side bar -->
                <div class="flex mt-5 sidebar">
                    <Navigation :showUser="showUser"></Navigation>
                </div>
                <div class="w-full">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
//Imports
import { Link, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import Navigation from "@/Components/app/Navigation.vue";
import NavDropdown from "@/Components/app/NavDropdown.vue";

//Data
const showUser = ref(false);

//Mathods
onMounted(() => {
    const page = usePage();
    // Redirect access to user page if user not system admin
    const role = JSON.parse(page.props.auth.user.role)[0];
    if (role == "system admin") showUser.value = true;
    else showUser.value = false;
});
</script>

<style scoped>
.h-btn {
    height: 35px;
}
.option {
    display: none; /* Hide the content */
}

@media (max-width: 790px) {
    .sidebar {
        display: none; /* Hide the content */
    }
    .option {
        display: block; /* Hide the content */
    }
}
</style>
