<template>
        <nav class="min-w-[200px]">
            <div class="px-3">
                <CreateNewDropDown/>
                <div class="py-3 grid justify-items-stretch">
                    <NavLink href="/" :active="$page.url == '/dashboard'">USER</NavLink>
                    <NavLink href="/">GROUP</NavLink>
                    <NavLink href="/">PROJECT</NavLink>
                </div>
            </div>
        </nav>
</template>
<script setup>
//Imports
import CreateNewDropDown from '@/Components/app/CreateNewDropdown.vue'
import NavLink from '../NavLink.vue';

//Uses
        
//Refs
        
//Props $ Emit
        
//Computed
        
//Mathods
        
//Hooks
        
</script>
<style scoped>
    
</style>