<template>
    <nav class="min-w-[200px]">
        <div class="px-3">
            <div class="py-3 grid justify-items-stretch">
                <NavLink
                    :href="route('page.project')"
                    :active="$page.url == '/project'"
                    :style="{
                        'pointer-events':
                            $page.url == '/project' ? 'none' : 'auto',
                    }"
                    >PROJECT</NavLink
                >
                <NavLink
                    :href="route('page.group')"
                    :active="$page.url == '/group'"
                    :style="{
                        'pointer-events':
                            $page.url == '/group' ? 'none' : 'auto',
                    }"
                    >GROUP</NavLink
                >

                <NavLink
                    :href="route('page.user')"
                    :active="$page.url == '/user'"
                    :style="{
                        'pointer-events':
                            $page.url == '/user' ? 'none' : 'auto',
                    }"
                    v-if="showUser"
                    >USER</NavLink
                >
            </div>
        </div>
    </nav>
</template>
<script setup>
//Imports
import NavLink from "../NavLink.vue";

//Props $ Emit
defineProps({
    showUser:Boolean
});
//Computed

//Hooks
</script>
<style scoped></style>
