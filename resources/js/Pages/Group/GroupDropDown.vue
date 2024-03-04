<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="h-btn mr-2 flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300transition ease-in-out duration-150"
            >
                +
                <ChevronDownIcon
                    class="-mr-1 h-5 w-5 text-gray-400"
                    aria-hidden="true"
                />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="py-1">
                    <MenuItem v-slot="{ active }" v-if="$page.url != '/group'">
                        <a
                            :class="[
                                active
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700',
                                'block px-4 py-2 text-sm',
                            ]"
                            :href="route('page.group')"
                            >GROUP</a
                        >
                    </MenuItem>
                    <MenuItem
                        v-slot="{ active }"
                        v-if="$page.url != '/project'"
                    >
                        <a
                            :class="[
                                active
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700',
                                'block px-4 py-2 text-sm',
                            ]"
                            :href="route('page.project')"
                            >PROJECT</a
                        >
                    </MenuItem>
                    <MenuItem v-slot="{ active }" v-if="$page.url != '/user'">
                        <a
                            type="submit"
                            :class="[
                                active
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700',
                                'block w-full px-4 py-2 text-left text-sm',
                            ]"
                            :href="route('page.user')"
                            >USER</a
                        >
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
</script>
<style scoped>
.h-btn {
    height: 35px;
}
</style>
